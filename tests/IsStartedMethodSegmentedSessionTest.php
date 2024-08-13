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

class IsStartedMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_check_if_session_is_active(): void
    {
        $session = new SegSession('da-segment');

        $this->assertTrue($session->isStarted()); // always auto-started

        $session->start();

        $this->assertTrue($session->isStarted()); // should still be started
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_check_if_session_is_active_when_session_started_outside_library(): void
    {
        session_start();
        $session = new SegSession('da-segment');

        $this->assertTrue($session->isStarted()); // always auto-started

        $session->start();

        $this->assertTrue($session->isStarted()); // should still be started
    }
}
