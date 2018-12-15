<?php
/**
 * PHP: Nelson Martell Library file
 *
 * Content:
 * - Interface definition:  [NelsonMartell]  IEquatable
 *
 * Copyright © 2014-2017 Nelson Martell (http://nelson6e65.github.io)
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2014-2017 Nelson Martell
 * @link      http://nelson6e65.github.io/php_nml/
 * @since     v0.1.1
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 * */

namespace NelsonMartell;

/**
 * Provee un método para comparar igualdad entre objetos del mismo tipo, o
 * compatibles.
 *
 * @author Nelson Martell <nelson6e65@gmail.com>
 * */
interface IEquatable
{

    /**
     * Indica si el objeto especificado es igual a la instancia actual.
     *
     * @param mixed $other Another object to compare equality.
     *
     * @return bool
     * */
    public function equals($other);
}
