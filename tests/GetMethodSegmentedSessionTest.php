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

class GetMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_attribute_if_exists(): void
    {
        $session = new SegSession('da-segment');

        $session->set('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));

        // Verify that set item was inside the segment in $_SESSION and not directly in $_SESSION
        $this->assertArrayHasKey('foo', $_SESSION[$session->getSegmentName()]);
        $this->assertEquals('bar', $_SESSION[$session->getSegmentName()]['foo']);
        $this->assertArrayNotHasKey('foo', $_SESSION);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_attribute_if_exists_when_session_started_outside_library(): void
    {
        session_start();

        $session = new SegSession('da-segment');

        $session->set('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));

        // Verify that set item was inside the segment in $_SESSION and not directly in $_SESSION
        $this->assertArrayHasKey('foo', $_SESSION[$session->getSegmentName()]);
        $this->assertEquals('bar', $_SESSION[$session->getSegmentName()]['foo']);
        $this->assertArrayNotHasKey('foo', $_SESSION);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_default_value_if_not_exists(): void
    {
        $session = new SegSession('da-segment');

        $this->assertNull($session->get('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_default_value_if_not_exists_when_session_started_outside_library(): void
    {
        session_start();

        $session = new SegSession('da-segment');

        $this->assertNull($session->get('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_custom_default_value_if_not_exists(): void
    {
        $session = new SegSession('da-segment');

        $this->assertEquals('bar', $session->get('foo', 'bar'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_custom_default_value_if_not_exists_when_session_started_outside_library(): void
    {
        session_start();

        $session = new SegSession('da-segment');

        $this->assertEquals('bar', $session->get('foo', 'bar'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_not_get_attribute_defined_outside_library(): void
    {
        session_start();

        $_SESSION['foo'] = 'bar';

        $session = new SegSession('da-segment');

        $this->assertNull($session->get('foo'));
    }
}
