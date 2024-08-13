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

class RegenerateIdMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_regenerate_session_id_without_deleting_old_session(): void
    {
        $session = new SegSession('da-segment');
        $sessionId = session_id();

        $this->assertTrue($session->regenerateId());

        $this->assertNotEquals($sessionId, session_id());
        
        $sessionId2 = session_id();

        $this->assertTrue($session->regenerateId());

        $this->assertNotEquals($sessionId2, session_id());
    }
}
