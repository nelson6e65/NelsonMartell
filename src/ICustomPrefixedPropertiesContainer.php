<?php declare(strict_types=1);
/**
 * PHP: Nelson Martell Library file
 *
 * Copyright © 2016-2019 Nelson Martell (http://nelson6e65.github.io)
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2016-2019 Nelson Martell
 * @link      http://nelson6e65.github.io/php_nml/
 * @since     0.6.0
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 * */

namespace NelsonMartell;

/**
 * Allows use custom getter/setter prefixes in a class in addition to default "get*" and "set*".
 * Access type of method should be marked as final since currently do not allows using multiple
 * custom properties.
 *
 * ## Notes
 * - Avoid the use of custom prefixes. If you need to, maybe you could try to rename methods instead first.
 *
 * @author Nelson Martell <nelson6e65@gmail.com>
 * @see PropertiesHandler
 * @since 0.6.0
 * */
interface ICustomPrefixedPropertiesContainer extends IStrictPropertiesContainer
{
    /**
     * Gets the custom prefix for getters methods.
     *
     * @return string
     */
    public static function getCustomGetterPrefix() : string;

    /**
     * Gets the custom prefix for setters methods.
     *
     * @return string
     */
    public static function getCustomSetterPrefix() : string;
}
