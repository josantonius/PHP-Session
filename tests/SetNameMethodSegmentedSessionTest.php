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

class SetNameMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_not_set_session_name_since_session_is_auto_started(): void
    {
        $session = new SegSession('da-segment'); // auto-starts session
        $originalSessionName = $session->getName();

        $session->setName('foo');

        $this->assertEquals($originalSessionName, session_name());
        $this->assertNotEquals('foo', session_name());
    }
}
