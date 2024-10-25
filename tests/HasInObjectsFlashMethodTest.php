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

class HasInObjectsFlashMethodTest extends TestCase
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

        $this->assertFalse($session->hasInObjectsFlash('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_right_values_before_and_after_setting_items_in_current_flash(): void
    {
        $session = new SegSession('da-segment');

        $this->assertFalse($session->hasInObjectsFlash('foo'));

        $session->setInObjectsFlash('foo', 'bar');

        $this->assertTrue($session->hasInObjectsFlash('foo'));

        // these values should be available in the current flash of the next instance below
        $session->setInSessionFlash('foo2', 'bar2');
        $session->setInSessionFlash('foo3', 'bar3');

        $session2 = new SegSession('da-segment');

        // previous next flash items should now be in the current flash for this instance
        $this->assertTrue($session2->hasInObjectsFlash('foo2'));
        $this->assertTrue($session2->hasInObjectsFlash('foo3'));
    }
}
