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

class RemoveMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_remove_attribute_even_if_not_exist(): void
    {
        $session = new SegSession('da-segment');
        
        $originalSegmentContent = $session->all();

        $session->remove('foo');

        $this->assertEquals($originalSegmentContent, $session->all());
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_should_remove_attribute_if_exist(): void
    {
        $session = new SegSession('da-segment');

        $_SESSION['foo'] = 'bar';

        $session->remove('foo');

        // Remove above should not affect $_SESSION['foo']
        $this->assertArrayHasKey('foo', $_SESSION);
        $this->assertEquals('bar', $_SESSION['foo']);
        
        // Set foo in the segment
        $session->set('foo', 'bar2');
        
        // foo should be in segment
        $this->assertTrue($session->has('foo'));
        $this->assertEquals('bar2', $session->get('foo'));
        
        $session->remove('foo'); // foo in the segment will be removed
        $this->assertFalse($session->has('foo'));
        
        // $_SESSION['foo'] should still remain unaffected
        $this->assertArrayHasKey('foo', $_SESSION);
        $this->assertEquals('bar', $_SESSION['foo']);
    }
}
