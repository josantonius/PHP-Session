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

class GetAllFromNextFlashMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_should_return_empty_array_on_newly_created_segment(): void
    {
        $session = new SegSession('da-segment');
        
        $this->assertEquals([], $session->getAllFromNextFlash());
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_should_return_right_values_before_and_after_setting_items_in_next_flash(): void
    {
        $session = new SegSession('da-segment');
        
        $session->setInNextFlash('foo', 'bar');
        $session->setInNextFlash('bar', 'foo');
        
        $this->assertEquals(
            ['foo'=>'bar', 'bar'=>'foo'],
            $session->getAllFromNextFlash()
        );
        
        // these values should not be available in the next flash of the next instance below
        $session->setInNextFlash('foo2', 'bar2');
        $session->setInNextFlash('foo3', 'bar3');
        
        $session2 = new SegSession('da-segment');
        
        // previous next flash items should not  be in the next flash for this instance
        $this->assertEquals([], $session2->getAllFromNextFlash());
        
        // test the return [] scenario in getAllFromNextFlash
        $session2->getStorage()->clear(); // we clear the actual session, not just the segment
        $session2->getStorage()->destroy(); // we destroy the actual session, not just the segment
        
        $this->assertEquals([], $session2->getAllFromNextFlash());
    }
}
