<?php
/**
 * PHP: Nelson Martell Library file
 *
 * Copyright © 2013-2015 Nelson Martell (http://nelson6e65.github.io)
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2013-2015 Nelson Martell
 * @link      http://nelson6e65.github.io/php_nml/
 * @since     0.1.1
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 * */

namespace NelsonMartell;

use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;

/**
 * Represents a PHP object type, and provides some properties and methods to
 * describe some info about itself.
 *
 * @author Nelson Martell <nelson6e65@gmail.com>
 * @since 0.1.1
 *
 * @property-read string        $name      Gets the name of this Type. This property is read-only.
 * @property-read string        $shortName Gets the abbreviated name of class, in other words, without the namespace.
 *   This property is read-only.
 * @property-read string|null   $namespace Gets the namespace name of this class. If this Type is not a class, this
 *   property is set to `null`. This property is read-only.
 * @property-read string|null   $methods   Gets the public|protected methods (ReflectionMethod) of this Type. This
 *   property is read-only.
 * @property-read string|null   $vars      Gets the public|protected properties (ReflectionProperty) of this Type.
 *   This property is read-only.
 *
 * */
final class Type extends StrictObject implements IEquatable
{

    /**
     * Gets the type of specified $obj and collect some info about itself.
     *
     * @param mixed $obj Target object.
     * */
    public function __construct($obj)
    {
        parent::__construct();

        $name = gettype($obj);
        $shortname = null;
        $namespace = null;
        $vars = null;
        $methods = null;
        $ref = null;

        switch ($name) {
            case 'object':
                $ref = new ReflectionClass($obj);
                $name = $ref->getName();
                $shortName = $ref->getShortName();
                $namespace = $ref->getNamespaceName();
                break;

            case 'resource':
                $shortName = get_resource_type($obj);
                $name = 'resource: '.$shortName;
                $vars = [];
                $methods = [];
                break;

            default:
                $shortName = $name;
                $vars = [];
                $methods = [];
        }

        $this->name = $name;
        $this->shortName = $shortName;
        $this->namespace = $namespace;
        $this->vars = $vars;
        $this->methods = $methods;
        $this->reflectionObject = $ref;
    }

    private $reflectionObject = null;

    private $name;

    /**
     * Getter for `$name` property.
     *
     * @return string
     * @see Type::$name
     * */
    protected function getName()
    {
        return $this->name;
    }

    private $shortName = null;

    /**
     * Getter for `$shortName` property.
     *
     * @return string
     * @see Type::$shortName
     * */
    public function getShortName()
    {
        return $this->shortName;
    }

    private $namespace;

    /**
     * Getter for `$namespace` property.
     *
     * @return string|null
     * @see    Type::$namespace
     * */
    public function getNamespace()
    {
        return $this->namespace;
    }

    private $vars = null;

    /**
     * Getter for `$vars` property.
     *
     * @return array
     */
    public function getVars()
    {
        if ($this->vars == null) {
            $this->vars = $this->reflectionObject->getProperties(
                ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
            );
        }
        return $this->vars;
    }

    private $methods = null;

    /**
     * Getter for `$methods` property.
     *
     * @return array
     * @see    Type::$methods
     */
    public function getMethods()
    {
        if ($this->methods == null) {
            $this->methods = $this->reflectionObject->getMethods(
                ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
            );
        }
        return $this->methods;
    }

    /**
     * Checks if the type has a method.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasMethod($name)
    {
        if ($this->reflectionObject !== null) {
            return $this->reflectionObject->hasMethod($name);
        }

        return false;
    }

    /**
     * Determines if instances of this Type can be converted to string.
     *
     *
     * @return bool
     */
    public function canBeString()
    {
        if ($this->isNull() || $this->isScalar() || $this->hasMethod('__toString')) {
            return true;
        }

        return false;
    }

    /**
     * Determina si este Type es `null`.
     *
     * @return bool `true` if this type is `null`; other case, `false`.
     * */
    public function isNull()
    {
        if ($this->name == 'NULL' || $this->name == 'null') {
            return true;
        }

        return false;
    }

    /**
     * Determina si este Type NO es `null`.
     *
     * @return bool `true` if this type is NOT `null`; other case, `false`.
     * */
    public function isNotNull()
    {
        return !$this->isNull();
    }


    /**
     * Determina si este Type es una clase personalizada.
     *
     * @return bool `true`, if this Type is a custom class; another case,
     *   `false`.
     * */
    public function isCustom()
    {
        return !$this->isValueType() && $this->isNotNull();
    }

    /**
     * Determinate if this type is scalar.
     *
     * @return bool
     * @see    \is_scalar()
     * */
    public function isScalar()
    {
        $r = false;

        switch ($this->name) {
            case 'boolean':
            case 'integer':
            case 'double':
            case 'string':
                $r = true;
                break;

            default:
                $r = false;
        }

        return $r;
    }

    /**
     * Determina si este Type es de tipo valor.
     *
     * @return bool
     * */
    public function isValueType()
    {
        if ($this->isScalar() || $this->name === 'array') {
            return true;
        }

        return false;
    }

    /**
     * Determina si este Type es de tipo referencia.
     *
     * @return bool
     * */
    public function isReferenceType()
    {
        return !$this->isValueType();
    }

    /**
     * Convierte la instancia actual en su representación en cadena.
     *
     * @return string
     * */
    public function toString()
    {
        $s = $this->name;

        if ($this->isCustom()) {
            $s = sprintf("object (%s)", $s);
        }

        return $s;
    }


    /**
     * @param Type|mixed $other
     *
     * @return bool
     */
    public function equals($other)
    {
        if ($other instanceof Type) {
            return $this->name == $other->name;
        } else {
            return false;
        }
    }

    /**
     * Obtiene el tipo del objeto especificado.
     * Es un alias para el constructor de Type.
     *
     * @param mixed $obj
     *
     * @return     Type
     * @deprecated 0.5.1 Use constructor instead.
     * */
    public static function typeof($obj)
    {
        return new static($obj);
    }
}
