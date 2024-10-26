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

class SetMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_set_attribute_if_session_was_started(): void
    {
        $session = new SegSession('da-segment');

        $session->set('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_set_attribute_if_native_session_was_started(): void
    {
        session_start();

        $session = new SegSession('da-segment');

        $session->set('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));
    }
}
