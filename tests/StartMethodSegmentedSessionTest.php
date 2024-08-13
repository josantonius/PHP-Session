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

class StartMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_session_should_always_be_started_since_it_gets_auto_started_in_constructor(): void
    {
        $session = new SegSession('da-segment');

        $this->assertTrue($session->start());

        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());

        $this->assertTrue(isset($_SESSION));

        // Call again
        $this->assertTrue($session->start());

        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());

        $this->assertTrue(isset($_SESSION));
    }
}
