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

namespace NelsonMartell\Test\Helpers;

use NelsonMartell as NML;
use NelsonMartell\Extensions\Text;
use NelsonMartell\IEquatable;

/**
 * Test helper for classes implementing ``NelsonMartell\IEquatable`` interface.
 *
 * Note: Classes using this trait MUST use ConstructorMethodTester and ExporterPlugin traits too.
 *
 * @author Nelson Martell <nelson6e65@gmail.com>
 * */
trait IEquatableTester
{
    abstract public function getTargetClassName(); // use ConstructorMethodTester;
    abstract public function getTargetClassReflection(); // use ConstructorMethodTester;
    abstract public function export($obj, $depth = 2, $short = false); // use plugin/ExporterPlugin;

    /**
     * Datasets for ``testIEquatableEqualsMethod(bool $expected, IEquatable $left, mixed $right)``.
     *
     * @return array
     */
    abstract public function IEquatableMethodArgumentsProvider();


    /**
     * @testdox Can check if instances are equals to other objects
     * @dataProvider IEquatableMethodArgumentsProvider
     */
    public function testIEquatableEqualsMethod($expected, IEquatable $left, $right)
    {
        $actual = $left->equals($right);

        $message = Text::format(
            '$obj->{method}({right}); // Returned: {actual} ($obj: {left})',
            [
                'method' => 'equals',
                'left'   => static::export($left),
                'right'  => static::export($right),
                'actual' => static::export($actual)
            ]
        );

        $this->assertInternalType('boolean', $actual, $message);

        if (!is_bool($expected)) {
            throw new InvalidArgumentException(Text::format(
                '1st argument of data provider should be of "boolean" type, "{0}" given.',
                NML\typeof($expected)
            ));
        }

        $this->assertEquals($expected, $actual, $message);

        $this->assertTrue($left->equals($left), '[Shold be equal to itself]');
    }

    /**
     * @testdox Is compliant with ``NelsonMartell\IEquatable`` interface
     * @depends testIEquatableEqualsMethod
     */
    public function testIsCompliantWithIEquatableIterface()
    {
        $message = Text::format(
            '"{0}" do not implements "{1}" interface.',
            $this->getTargetClassName(),
            IEquatable::class
        );

        $this->assertContains(IEquatable::class, $this->getTargetClassReflection()->getInterfaceNames(), $message);
    }
}
