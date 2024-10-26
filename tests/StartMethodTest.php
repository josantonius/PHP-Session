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
use Josantonius\Session\Session;
use Josantonius\Session\Exceptions\HeadersSentException;
use Josantonius\Session\Facades\Session as SessionFacade;
use Josantonius\Session\Exceptions\SessionStartedException;
use Josantonius\Session\Exceptions\WrongSessionOptionException;

class StartMethodTest extends TestCase
{
    private Session $session;

    public function setUp(): void
    {
        parent::setUp();

        $this->session = new Session();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_start_session(): void
    {
        $this->assertTrue($this->session->start());

        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());

        $this->assertTrue(isset($_SESSION));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_accept_options(): void
    {
        $this->session->start(['cookie_lifetime' => 8000, 'name' => 'boooo']);

        $this->assertEquals(8000, ini_get('session.cookie_lifetime'));
        $this->assertEquals('boooo', $this->session->getName());
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_fail_with_wrong_options(): void
    {
        $this->expectException(WrongSessionOptionException::class);

        $this->session->start(['foo' => 'bar']);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_fail_when_session_is_already_active(): void
    {
        $this->session->start();

        $this->expectException(SessionStartedException::class);

        $this->session->start();
    }

    public function test_should_fail_when_headers_sent(): void
    {
        $this->expectException(HeadersSentException::class);

        $this->session->start();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_be_available_from_the_facade(): void
    {
        $facade = new SessionFacade();

        $this->assertTrue($facade->start());
    }
}
