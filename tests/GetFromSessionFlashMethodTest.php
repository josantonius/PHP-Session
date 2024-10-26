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

class GetFromSessionFlashMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_null_default_for_non_existent_attribute(): void
    {
        $session = new SegSession('da-segment');

        $this->assertNull($session->getFromSessionFlash('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_custom_default_for_non_existent_attribute(): void
    {
        $session = new SegSession('da-segment');

        $this->assertEquals('bar', $session->getFromSessionFlash('foo', 'bar'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_right_values_after_setting_items_in_current_flash(): void
    {
        $session = new SegSession('da-segment');

        $session->setInSessionFlash('foo', 'bar');
        $session->setInSessionFlash('bar', 'foo');

        $this->assertEquals('bar', $session->getFromSessionFlash('foo'));
        $this->assertEquals('foo', $session->getFromSessionFlash('bar'));

        // these values should NOT be available in the next flash of the next instance below
        $session->setInSessionFlash('foo2', 'bar2');
        $session->setInSessionFlash('foo3', 'bar3');

        $session2 = new SegSession('da-segment');

        // previous next flash items should NOT be in the next flash for this instance
        $this->assertNotEquals('bar2', $session2->getFromSessionFlash('foo2'));
        $this->assertNotEquals('bar3', $session2->getFromSessionFlash('foo3'));
    }
}