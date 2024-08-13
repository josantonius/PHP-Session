<?php

/*
 * This file is part of https://github.com/josantonius/php-session repository.
 *
 * (c) Josantonius <hello@josantonius.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */

namespace Josantonius\Session\Tests;

use PHPUnit\Framework\TestCase;
use Josantonius\Session\FlashableSessionSegment as SegSession;

class HasInNextFlashMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_false_on_newly_created_segment(): void
    {
        $session = new SegSession('da-segment');

        $this->assertFalse($session->hasInNextFlash('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_right_values_before_and_after_setting_items_in_next_flash(): void
    {
        $session = new SegSession('da-segment');

        $this->assertFalse($session->hasInNextFlash('foo'));

        $session->setInNextFlash('foo', 'bar');

        $this->assertTrue($session->hasInNextFlash('foo'));

        // these values should not be available in the next flash of the next instance below
        $session->setInNextFlash('foo2', 'bar2');
        $session->setInNextFlash('foo3', 'bar3');

        $session2 = new SegSession('da-segment');

        // previous next flash items should not  be in the next flash for this instance
        $this->assertFalse($session2->hasInNextFlash('foo2'));
        $this->assertFalse($session2->hasInNextFlash('foo3'));
    }
}
