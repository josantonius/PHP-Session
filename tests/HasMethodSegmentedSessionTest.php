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

class HasMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_check_if_attribute_exists(): void
    {
        $session = new SegSession('da-segment');
        $session->set('foo', 'bar');

        $this->assertTrue($session->has('foo'));
        $this->assertFalse($session->has('bar'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_check_attribute_defined_outside_library(): void
    {
        session_start();
        $_SESSION['foo'] = 'bar';

        $session = new SegSession('da-segment');
        $this->assertFalse($session->has('foo'));
    }
}
