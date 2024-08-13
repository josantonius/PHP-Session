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

class AllMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_all_attributes_in_segment(): void
    {
        $session = new SegSession('da-segment');
        
        $this->assertIsArray($_SESSION);

        $session->set('foo', 'bar');

        $this->assertEquals(['foo' => 'bar', SegSession::FLASH_DATA_FOR_NEXT_REQUEST => []], $session->all());
        
        // foo should only be inside $_SESSION[$session->getSegmentName()]
        $this->assertArrayNotHasKey('foo', $_SESSION);
        
        // SegSession::FLASH_DATA_FOR_NEXT_REQUEST should only be inside $_SESSION[$session->getSegmentName()]
        $this->assertArrayNotHasKey(SegSession::FLASH_DATA_FOR_NEXT_REQUEST, $_SESSION);
        
        $this->assertEquals(
            [
                $session->getSegmentName() => 
                    [
                        'foo' => 'bar', SegSession::FLASH_DATA_FOR_NEXT_REQUEST => []
                    ]
            ], 
            $_SESSION
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_get_all_attributes_in_segment_when_session_started_outside_library(): void
    {
        session_start();
        
        $this->assertIsArray($_SESSION);
        
        $session = new SegSession('da-segment');
        
        $_SESSION['top-key'] = 'top-value';

        $session->set('foo', 'bar');

        $this->assertEquals(['foo' => 'bar', SegSession::FLASH_DATA_FOR_NEXT_REQUEST => []], $session->all());
        
        // foo should only be inside $_SESSION[$session->getSegmentName()]
        $this->assertArrayNotHasKey('foo', $_SESSION);
        
        // SegSession::FLASH_DATA_FOR_NEXT_REQUEST should only be inside $_SESSION[$session->getSegmentName()]
        $this->assertArrayNotHasKey(SegSession::FLASH_DATA_FOR_NEXT_REQUEST, $_SESSION);
        
        // Make sure both data set in $_SESSION from outside & within this
        // library are where they should be
        $this->assertEquals(
            [
                'top-key' => 'top-value',
                $session->getSegmentName() => 
                    [
                        'foo' => 'bar', SegSession::FLASH_DATA_FOR_NEXT_REQUEST => []
                    ]
            ], 
            $_SESSION
        );
    }
}
