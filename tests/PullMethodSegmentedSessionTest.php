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

class PullMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_pull_attribute_and_return_the_value_if_exists(): void
    {
        $session = new SegSession('da-segment');

        $_SESSION['foo'] = 'bar';

        // foo has not yet been set in the segment
        // default value of null should be returned
        $this->assertNull($session->pull('foo'));

        // pull above should not affect $_SESSION['foo']
        $this->assertArrayHasKey('foo', $_SESSION);
        
        $session->set('foo', 'bar2'); // this will be stored in the segment
        
        // we are pulling the foo in the segment & not $_SESSION['foo']
        $this->assertEquals('bar2', $session->pull('foo'));
        
        // foo set directly in $_SESSION remains unchanged
        $this->assertArrayHasKey('foo', $_SESSION);
        $this->assertEquals('bar', $_SESSION['foo']);
        
        // foo in segment has been removed
        $this->assertIsArray($_SESSION[$session->getSegmentName()]);
        $this->assertArrayNotHasKey('foo', $_SESSION[$session->getSegmentName()]);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_default_value_if_attribute_not_exists(): void
    {
        $session = new SegSession('da-segment');

        $this->assertNull($session->pull('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_custom_default_value_if_attribute_not_exists(): void
    {
        $session = new SegSession('da-segment');

        $this->assertEquals('bar', $session->pull('foo', 'bar'));
    }
}
