<?php

/**
 * PHP: Nelson Martell Library file
 *
 * Copyright © 2019 Nelson Martell (http://nelson6e65.github.io)
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2019 Nelson Martell
 * @link      http://nelson6e65.github.io/php_nml/
 * @since     1.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
 * */

declare(strict_types=1);

namespace NelsonMartell\Test\TestCase\Extensions;

use NelsonMartell\Test\DataProviders\Extensions\ObjectsTestProvider;
use PHPUnit\Framework\TestCase;

/**
 *
 * @author Nelson Martell <nelson6e65@gmail.com>
 * @internal
 * @group Criticals
 *
 * @since 1.0.0
 * */
class ObjectsTest extends TestCase
{
    use ObjectsTestProvider;
}
