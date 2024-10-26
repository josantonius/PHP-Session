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

class GetIdMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_session_id(): void
    {
        $session = new SegSession('da-segment');

        // Should have the same id as it's internal storage object
        $this->assertEquals($session->getId(), $session->getStorage()->getId());
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_session_id_when_session_started_outside_library(): void
    {
        session_start();

        $session = new SegSession('da-segment');

        // Should have the same id as it's internal storage object
        $this->assertEquals($session->getId(), $session->getStorage()->getId());
    }
}
