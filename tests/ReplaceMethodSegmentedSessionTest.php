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

class ReplaceMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_add_attributes_if_not_exist(): void
    {
        $session = new SegSession('da-segment');

        $_SESSION['bar'] = 'foo';

        // foo has not yet been set in the segment
        $this->assertFalse($session->has('foo'));
        
        // bar should not be in the segment
        $this->assertFalse($session->has('bar'));
        
        $session->replace(['foo' => 'bar']);
        
        // foo was just set via the call to replace above
        $this->assertTrue($session->has('foo'));

        // $_SESSION['bar'] should be untouched
        $this->assertEquals('foo', $_SESSION['bar']);
        
        // assert that replaced value is present
        $this->assertEquals('bar', $session->get('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_replace_attributes_if_exist(): void
    {
        $session = new SegSession('da-segment');
        
        $session->set('foo', 'bar');
        $session->set('bar', 'foo');

        $session->replace(['foo' => 'val']);
        
        $this->assertEquals('val', $session->get('foo'));
        $this->assertEquals('foo', $session->get('bar'));
        
        $this->assertArrayNotHasKey('foo', $_SESSION);
        $this->assertArrayNotHasKey('bar', $_SESSION);
    }
}
