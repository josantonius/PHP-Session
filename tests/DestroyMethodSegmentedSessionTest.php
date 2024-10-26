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

class DestroyMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_clear_only_session_segment(): void
    {
        $session = new SegSession('da-segment');

        $_SESSION['bar'] = 'foo';

        $session->set('foo', 'bar');

        $segmBeforeClearing = [
            'foo' => 'bar',
            SegSession::FLASH_DATA_FOR_NEXT_REQUEST => [],
        ];

        // Verify segment data before clearing
        $this->assertEquals($segmBeforeClearing, $_SESSION[$session->getSegmentName()]);

        // Verify that it always returns true
        $this->assertTrue($session->destroy());

        // Cleared segment should only contain an empty array for flash data
        $this->assertEquals(
            [SegSession::FLASH_DATA_FOR_NEXT_REQUEST => [],],
            $_SESSION[$session->getSegmentName()]
        );

        // Items set directly in $_SESSION should still be present & not cleared
        $this->assertArrayHasKey('bar', $_SESSION);
        $this->assertEquals('foo', $_SESSION['bar']);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_clear_only_session_segment_when_session_started_outside_library(): void
    {
        session_start();

        $_SESSION['bar'] = 'foo';

        $session = new SegSession('da-segment');

        $_SESSION['bar1'] = 'foo1';

        $session->set('foo', 'bar');

        $segBeforeClearing = [
            'foo' => 'bar',
            SegSession::FLASH_DATA_FOR_NEXT_REQUEST => [],
        ];

        // Verify segment data before clearing
        $this->assertEquals($segBeforeClearing, $_SESSION[$session->getSegmentName()]);

        // Verify that it always returns true
        $this->assertTrue($session->destroy());

        // Cleared segment should only contain an empty array for flash data
        $this->assertEquals(
            [SegSession::FLASH_DATA_FOR_NEXT_REQUEST => [],],
            $_SESSION[$session->getSegmentName()]
        );

        // Items set directly in $_SESSION should still be present & not cleared
        $this->assertArrayHasKey('bar', $_SESSION);
        $this->assertArrayHasKey('bar1', $_SESSION);
        $this->assertEquals('foo', $_SESSION['bar']);
        $this->assertEquals('foo1', $_SESSION['bar1']);
    }
}
