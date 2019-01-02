<?php
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
 * @since     v0.6.0
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 * */

namespace NelsonMartell\Test\DataProviders;

use NelsonMartell\StrictObject;
use NelsonMartell\Test\Helpers\ConstructorMethodTester;
use NelsonMartell\Test\Helpers\ExporterPlugin;
use NelsonMartell\Test\Helpers\IComparerTester;

/**
 *
 * @author Nelson Martell <nelson6e65@gmail.com>
 * @internal
 * */
trait ObjectTestProvider
{
    use ExporterPlugin;
    use ConstructorMethodTester;
    use IComparerTester;

    # ConstructorMethodTester

    public function getTargetClassName()
    {
        return StrictObject::class;
    }

    public function goodConstructorArgumentsProvider()
    {
        return [[]];
    }


    public function badConstructorArgumentsProvider()
    {
        return null;
    }
    #


    # IComparerTester
    public function compareMethodArgumentsProvider()
    {
        $obj = new \stdClass();
        $obj->one = 1;
        $obj->nine = 9;

        $args = [
            'integers: same value, +-'      => [1, 5, -5],
            'integers: same value, -+'      => [-1, -5, 5],
            'integers: same value, --'      => [0, -5, -5],
            'integers: same value, ++'      => [0, 5, 5],
            'integers: different value, +-' => [1, 90, -8],
            'integers: different value, -+' => [-1, -8, 90],
            'integers: different value, --' => [1, -8, -90],
            'integers: different value, ++' => [-1, 8, 90],
            'strings: same'                 => [0, 'world', 'world'],
            'strings: leading space, <'     => [-1, 'world', 'world '],
            'strings: leading space, >'     => [1, 'world ', 'world'],
            'strings: different chars, >'   => [1, 'hola', 'hello'],
            'strings: different chars, <'   => [-1, 'hello', 'hola'],
            'arrays: same'                  => [0, ['one' => 'world'], ['one' => 'world']],
            'arrays: different count, >'    => [1, ['hola', 'mundo'], ['hello']],
            'arrays: different count, <'    => [-1, ['hello'], ['hola', 'mundo']],
            'array > array (values)'        => [1, ['hola', 'mundo'], ['hello', 'world']],
            'array < array (values)'        => [-1, ['hello', 'world'], ['hola', 'mundo']],
            'array < array (keys)'          => [-1, ['hola', 'mundo'], ['one' => 'hello', 'two' => 'world']],
            'stdClass = stdClass'           => [0, $obj, $obj],
            'stdClass (empty) < stdClass'   => [-1, new \stdClass, $obj],
        ];

        return $args;
    }

    public function compareMethodArraysProvider()
    {
        return [
            'integer[]'           => [[-67, -9, 0, 4, 5, 6]],
            'string[]'            => [['a', 'b', 'c', 'd', 'z', 'z1']],
        ];
    }
    #
}
