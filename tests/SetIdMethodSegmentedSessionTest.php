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

class SetIdMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_not_set_session_id_since_session_is_auto_started(): void
    {
        $session = new SegSession('da-segment');
        $originalId = $session->getId();
        
        $session->setId('foo');

        $this->assertEquals($originalId, session_id());
        $this->assertNotEquals('foo', session_id());
    }
}
