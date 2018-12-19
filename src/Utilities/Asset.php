<?php
/**
 * PHP: Nelson Martell Library file
 *
 * Content:
 * - Class definition:  [NelsonMartell\Utilities]  Asset
 *
 * Copyright © 2014-2017 Nelson Martell (http://nelson6e65.github.io)
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2014-2017 Nelson Martell
 * @link      http://nelson6e65.github.io/php_nml/
 * @since     0.1.1
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 * */

namespace NelsonMartell\Utilities;

use InvalidArgumentException;
use NelsonMartell\StrictObject;
use NelsonMartell\Version;

use function NelsonMartell\msg;
use function NelsonMartell\typeof;

/**
 * Representa un recurso estático de una página, como elementos js y css
 * organizados de una manera predeterminada en subdirectorios.
 * Contiene métodos y propiedades para obtener las rutas de los directorios
 * y recursos entre sus diferentes versiones.
 *
 * @author Nelson Martell <nelson6e65@gmail.com>
 * @since 0.1.1
 * @deprecated 0.7.1
 * */
abstract class Asset extends StrictObject
{
    /**
     * Crea una nueva instancia de la clase Asset.
     *
     * @param string|null               $name     Nombre humano del recurso.
     * @param array|string|Version|null $versions Versión(es) del recurso.
     * @param string|null               $cdnUri   URL del recurso en un
     *   servidor CDN.
     */
    public function __construct($name = null, $versions = null, $cdnUri = null)
    {
        parent::__construct();
        unset($this->Name, $this->Versions, $this->ShortName, $this->CdnUri, $this->RootDirectory);

        if ($name == null) {
            $this->name = '';
            $this->shortName = '';
        } else {
            $this->Name = $name;
        }

        if ($this->Name == '' && $versions != null) {
            $args = [
                'name'     => 'versions',
                'pos'      => 1,
                'name2'    => 'name',
                'pos2'     => 0,
            ];

            $msg = msg('Invalid argument value.');
            $msg .= msg(
                ' "{name}" (position {pos}) can not be specified if "{name2}" (position {pos2}) is "null".',
                $args
            );

            throw new InvalidArgumentException($msg);
        }

        if ($versions == null) {
            $versions = array();
        }

        if (is_array($versions)) {
            $this->versions = array();

            if (count($versions) > 0) {
                $i = 0;
                foreach ($versions as $version) {
                    $v = $version;
                    if (!($v instanceof Version)) {
                        try {
                            $v = Version::parse($version);
                        } catch (InvalidArgumentException $e) {
                            $args = [
                                'name'     => 'versions',
                                'pos'      => 1,
                                'expected' => typeof(new Version(1, 0))->Name,
                                'actual'   => typeof($version)->Name,
                            ];

                            $msg = msg('Invalid argument value.');
                            $msg .= msg(
                                ' "{name}" (position {pos}) must to be an array of "{expected}" elements (or any'.
                                ' parseable into "{expected}"); the array given has an invalid "{actual}" element.',
                                $args
                            );

                            throw new InvalidArgumentException($msg, 0, $e);
                        }
                    }

                    $this->versions[$i] = $v;

                    $i += 1;
                }
            }
        } else {
            // Trata de convertir $versions en un objeto Versión
            try {
                $v = Version::parse($versions);
            } catch (InvalidArgumentException $e) {
                $args = [
                    'name'     => 'versions',
                    'pos'      => 1,
                    'expected' => typeof(new Version(1, 0))->Name,
                    'actual'   => typeof($versions)->Name,
                ];

                $msg = msg('Invalid argument value.');
                $msg .= msg(
                    ' "{name}" (position {pos}) must to be an array of "{expected}" elements, an instance of'.
                    ' "{expected}" or any object parseable into "{expected}"; "{actual}" given.',
                    $args
                );

                throw new InvalidArgumentException($msg, 0, $e);
            }

            $this->versions = array($v);
        }

        $this->CdnUri = $cdnUri;
    }


    /**
     * Obtiene o establece el nombre original 'humano' del recurso.
     * A partir de éste se determinará la ruta y el nombre real del archivo
     * (que, por defecto, será éste mismo pero convertido en minúsculas y
     * reemplazando sus espacios en blanco por guiones (' ' -> '-')).
     *
     * @var string Nombre del recurso
     * @see Asset::$shortName
     * */
    public $Name;
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        if (!is_string($value)) {
            $args = [
                'class'    => typeof($this)->Name,
                'property' => 'Name',
                'pos'      => 0,
                'expected' => 'string',
                'actual'   => typeof($value)->Name,
            ];

            $msg = msg('Invalid argument type.');
            $msg .= msg(
                ' "{class}::{property}" must to be an instance of "{expected}"; "{actual}" given.',
                $args
            );

            throw new InvalidArgumentException($msg);
        }

        if (str_word_count($value) == 0) {
            $args = [
                'class'    => typeof($this)->Name,
                'property' => 'Name',
                'pos'      => 0,
                'expected' => 'string',
                'actual'   => typeof($value)->Name,
            ];

            $msg = msg('Invalid argument value.');
            $msg .= msg(
                ' "{class}::{property}" value can not be empty or whitespace.',
                $args
            );

            throw new InvalidArgumentException($msg);
        }

        $this->name = trim($value);

        $this->shortName = str_replace(' ', '-', strtolower($this->name));
    }

    /**
     * Obtiene el nombre real del recurso, que representa al nombre 'usable'
     * del recurso.
     * Esta propiedad es de sólo lectura.
     *
     * @var string Nombre del recurso en su forma generada
     * */
    public $ShortName;
    private $shortName;

    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Obtiene la lista de versiones.
     * Esta propiedad es de sólo lectura.
     *
     * @var List Lista de versiones del recurso
     * */
    public $Versions;
    private $versions;

    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Obtiene o establece el CDN del recurso.
     * Debe modificarse la URL, colocando '{v}' en vez de la versión.
     *
     * @example Un CDN para el JavaScript de jQuery UI v1.11.2 es:
     *   "//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"
     *   Entonces el valor de esta propiedad debe ser:
     *   "//ajax.googleapis.com/ajax/libs/jqueryui/{v}/jquery-ui"
     *
     * @var string CDN
     * */
    public $CdnUri;
    private $cdnUri;

    public function getCdnUri()
    {
        return $this->cdnUri;
    }

    public function setCdnUri($value)
    {
        $this->cdnUri = (string) $value;
    }


    /**
     * Obtiene el directorio principal del recurso.
     *
     * @var string Ruta inicial del recurso
     * */
    public $RootDirectory;
    public function getRootDirectory()
    {
        return $this->ShortName.'/';
    }

    const NEWEST = 'newest';

    const OLDEST = 'oldest';

    /**
     * Obtiene la ruta del directorio de la versión especificada. Si no se
     * especifica, se devuelve la versión más reciente.
     *
     * @param string|Version $version Versión a obtener. También puede
     *   tomar los valores 'newest' u 'oldest' para representar a la versión
     *   más nueva o más vieja, respectivamente.
     *
     * @return string Ruta del directorio de la versión especificada.
     * */
    public function getDirectoryPath($version = self::NEWEST)
    {
        $c = count($this->Versions);

        if ($c == 0) {
            throw new LogicException(msg('This Asset has not versions.'));
        }
        $v = $version;

        if ($version == self::OLDEST or $version == self::NEWEST) {
            $v = $this->Versions[0];

            if ($c > 1) {
                usort($this->versions, array("NelsonMartell\\Version", "Compare"));

                $v = $this->Versions[0];

                if ($version == self::NEWEST) {
                    $v = $this->Versions[$c - 1];
                }
            }
        } else {
            try {
                $v = Version::parse($version);
            } catch (InvalidArgumentException $e) {
                $args = [
                    'name'     => 'version',
                    'pos'      => 0,
                    'expected' => typeof(new Version(1, 0))->Name,
                    'actual'   => typeof($version),
                ];

                $msg = msg('Invalid argument type.');
                $msg .= msg(
                    ' "{name}" (position {pos}) must to be an instance of "{expected}" (or compatible);'.
                    ' "{actual}" given.',
                    $args
                );

                throw new InvalidArgumentException($msg);
            }

            if (array_search($v, $this->Versions) === false) {
                $msg = msg('Invalid argument value.');
                $msg .= msg(' Asset has not the version "{version}".', ['version' => $v]);

                throw new InvalidArgumentException($msg);
            }
        }

        return sprintf('%s%s/', $this->RootDirectory, $v);
    }


    /**
     * Obtiene la ruta del recurso de la versión especificada. Si no se
     * especifica, se devuelve la versión más reciente.
     *
     * @param string|Version $version Versión a obtener. También puede tomar los valores 'newest' u 'oldest' para
     *   representar a la versión más nueva o más vieja, respectivamente.
     * @param string         $append  Texto que se le anexará a la cadena de salida.
     *
     * @return string Ruta del recurso
     * */
    public function getResourcePath($version = self::NEWEST, $append = '')
    {

        $r = sprintf('%s%s%s', $this->getDirectoryPath($version), $this->ShortName, $append);

        return $r;
    }
}
