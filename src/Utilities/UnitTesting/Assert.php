<?php
/**
 * PHP: Nelson Martell Library file
 *
 * Content:
 * - Class definition:  [NelsonMartell\Utilities\UnitTesting]  Assert
 *
 * Copyright © 2015-2017 Nelson Martell (http://nelson6e65.github.io)
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2015-2017 Nelson Martell
 * @link      http://nelson6e65.github.io/php_nml/
 * @since     0.1.1
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 * */

namespace NelsonMartell\Utilities\UnitTesting;

use NelsonMartell\IEquatable;
use NelsonMartell\StrictObject;
use \Exception;

/**
 * Comprueba condiciones de pruebas.
 *
 *
 * @author  Nelson Martell <nelson6e65@gmail.com>
 * @since 0.1.1
 * @deprecated 0.7.1
 * */
final class Assert
{
    /**
     *
     *
     * @param mixed $expected
     * @param mixed $actual
     *
     * @return bool
     */
    private static function equals($expected, $actual)
    {
        if ($expected === $actual) {
            return true;
        }

        if ($expected instanceof StrictObject || $expected instanceof IEquatable) {
            if ($expected->equals($actual)) {
                return true;
            }
        } else {
            if ($expected == $actual) {
                return true;
            }
        }

        return false;
    }


    /**
     * Comprueba si los dos objetos especificados son iguales. Caso contrario, emite una
     * advertencia.
     *
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $msg      Custom message to append on assert failed.
     *
     * @return bool true si son iguales; false, en caso contrario.
     * */
    public static function areEqual($expected, $actual, $msg = '')
    {

        $equals = self::equals($expected, $actual);

        if (!$equals) {
            $a_string = $actual;
            $e_string = $expected;

            if (is_array($actual)) {
                $a_string = implode(', ', $actual);
            }

            if (is_array($expected)) {
                $e_string = implode(', ', $expected);
            }

            if (is_bool($actual)) {
                $a_string = $actual ? 'true' : 'false';
            }

            if (is_bool($expected)) {
                $e_string = $expected ? 'true' : 'false';
            }

            $error = sprintf(
                dgettext('nml', '%5$s failed. Expected: (%3$s) "%4$s". Actual: (%1$s) "%2$s".'),
                typeof($actual),
                $a_string,
                typeof($expected),
                $e_string,
                __METHOD__
            );

            if ($msg) {
                $error .= ' '.sprintf(dgettext('nml', 'Message: %s'), $msg);
            }

            trigger_error($error, E_USER_WARNING);
        }

        return $equals;
    }



    /**
     * Comprueba si los dos objetos especificados NO son iguales. En caso de que sí lo sean,
     * emite una advertencia.
     *
     *
     * @param mixed  $notExpected
     * @param mixed  $actual
     * @param string $msg      Custom message to append on assert failed.
     *
     * @return  boolean true si NO son iguales; false, en caso contrario.
     * */
    public static function areNotEqual($notExpected, $actual, $msg = '')
    {
        $not_equals = !self::equals($notExpected, $actual);

        if (!$not_equals) {
            $a_string = $actual;
            $ne_string = $notExpected;

            if (is_array($actual)) {
                $a_string = implode(', ', $actual);
            }

            if (is_array($notExpected)) {
                $ne_string = implode(', ', $notExpected);
            }

            if (is_bool($actual)) {
                $a_string = $actual ? 'true' : 'false';
            }

            if (is_bool($notExpected)) {
                $ne_string = $notExpected ? 'true' : 'false';
            }

            $error = sprintf(
                dgettext('nml', '%5$s failed. Not expected: (%3$s) "%4$s". Actual: (%1$s) "%2$s".'),
                Type::typeof($actual),
                $a_string,
                Type::typeof($notExpected),
                $ne_string,
                __METHOD__
            );

            if ($msg) {
                $error .= ' '.sprintf(dgettext('nml', 'Message: %s'), $msg);
            }

            trigger_error($error, E_USER_WARNING);
        }

        return $not_equals;
    }

    /**
     * Tests that the object is `true`.
     *
     * @param mixed $actual
     *
     * @return bool
     */
    public static function isTrue($actual)
    {
        return self::areEqual(true, $actual);
    }

    /**
     * Tests that the object is `false`.
     *
     * @param mixed $actual
     *
     * @return bool
     */
    public static function isFalse($actual)
    {
        return self::areEqual(false, $actual);
    }

    /**
     * Comprueba que, si al llamar un método público de un objeto, se obtiene una excepción del
     * tipo especificado.
     *
     *
     * @param   string $method_name Method name.
     * @param   mixed $obj Object to check.
     * @param   array $params Method params.
     * @param   Exception $expectedException Exception to check type. If null, checks any.
     * @param   string $msg Custom message to append on assert failed.
     * @return  boolean
     * */
    public static function methodThrowsException(
        $method_name,
        $obj,
        $params = array(),
        Exception $expectedException = null,
        $msg = ''
    ) {

        $equals = false;

        $expected = typeof($expectedException);
        $actual = typeof(null);

        try {
            call_user_func_array(array($obj, $method_name), $params);
        } catch (Exception $e) {
            $actual = typeof($e);
        }

        if ($actual->isNotNull()) {
            // Se lanzó la excepción...
            if ($expected->isNull()) {
                // ...pero no se especificó el tipo de excepción, es decir, puede ser cualquiera
                $equals = true;
            } else {
                // ...pero debe comprobarse si son del mismo tipo:
                $equals = self::equals($expected, $actual);

                if (!$equals) {
                    $error = sprintf(
                        dgettext('nml', '%1$s failed. Expected: "%2$s". Actual: "%3$s".'),
                        __METHOD__,
                        $expected,
                        $actual
                    );

                    if ($msg) {
                        $error .= ' '.sprintf(dgettext('nml', 'Message: %s'), $msg);
                    }

                    trigger_error($error, E_USER_WARNING);
                }
            }
        } else {
            // No se lanzó la excepción
            $actual = "No exception";

            if ($expected->isNull()) {
                $expected = "Any exception";
            }

            $error = sprintf(
                dgettext('nml', '%1$s failed. Expected: "%2$s". Actual: "%3$s".'),
                __METHOD__,
                $expected,
                $actual
            );

            if ($msg) {
                $error .= ' '.sprintf(dgettext('nml', 'Message: %s'), $msg);
            }

            trigger_error($error, E_USER_WARNING);
        }


        return $equals;
    }


    /**
     * Tests if a property trows an exception.
     *
     * @param mixed     $obj               Target object.
     * @param string    $property_name
     * @param mixed     $value
     * @param Exception $expectedException
     * @param string    $msg
     *
     * @return bool
     */
    public static function propertyThrowsException(
        $obj,
        $property_name,
        $value,
        $expectedException = null,
        $msg = ''
    ) {
        $equals = false;

        $expected = typeof($expectedException);
        $actual = typeof(null);

        try {
            $obj->$property_name = $value;
        } catch (Exception $e) {
            $actual = typeof($e);
        }

        if ($actual->isNotNull()) {
            // Se lanzó la excepción...
            if ($expected->isNull()) {
                // ...pero no se especificó el tipo de excepción, es decir, puede ser cualquiera
                $equals = true;
            } else {
                // ...pero debe comprobarse si son del mismo tipo:
                $equals = self::equals($expected, $actual);

                if (!$equals) {
                    $error = sprintf(
                        dgettext('nml', '%1$s failed. Expected: "%2$s". Actual: "%3$s".'),
                        __METHOD__,
                        $expected,
                        $actual
                    );

                    if ($msg) {
                        $error .= ' '.sprintf(dgettext('nml', 'Message: %s'), $msg);
                    }

                    trigger_error($error, E_USER_WARNING);
                }
            }
        } else {
            // No se lanzó la excepción
            $actual = "No exception";

            if ($expected->isNull()) {
                $expected = "Any exception";
            }

            $error = sprintf(
                dgettext('nml', '%1$s failed. Expected: "%2$s". Actual: "%3$s".'),
                __METHOD__,
                $expected,
                $actual
            );

            if ($msg) {
                $error .= ' '.sprintf(dgettext('nml', 'Message: %s'), $msg);
            }

            trigger_error($error, E_USER_WARNING);
        }


        return $equals;
    }
}
