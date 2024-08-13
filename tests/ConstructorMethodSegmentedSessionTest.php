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
use Josantonius\Session\FlashableSessionSegment as SegSession;
use Josantonius\Session\Exceptions\EmptySegmentNameException;
use Josantonius\Session\Exceptions\HeadersSentException;
use Josantonius\Session\Exceptions\SessionStartedException;
use Josantonius\Session\Exceptions\SessionNotStartedException;
use Josantonius\Session\Exceptions\WrongSessionOptionException;

class ConstructorMethodSegmentedSessionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_verify_segment_exists_in_session_and_session_options_were_properly_set(): void
    {
        $options = [
            'name' => 'boooooooo',
            'cookie_lifetime' => 8000,
        ];
        $segmentName = 'da-segment';
        $session = new SegSession($segmentName, null, $options);

        // Session was started outside the library.
        $this->assertTrue($session->isStarted());

        // Check that the segment name was properly set in the session object
        $this->assertEquals($segmentName, $session->getSegmentName());

        // Check that session options were not set in the constructor
        // since in this scenario the session was started outside the library.
        $this->assertEquals($options['name'], $session->getName());
        $this->assertEquals($options['cookie_lifetime'], ini_get('session.cookie_lifetime'));

        // check that flash was initialized
        $this->assertArrayHasKey($segmentName, $_SESSION);
        $this->assertEquals([SegSession::FLASH_DATA_FOR_NEXT_REQUEST => []], $_SESSION[$segmentName]);
        $this->assertArrayHasKey(SegSession::FLASH_DATA_FOR_NEXT_REQUEST, $_SESSION[$segmentName]);
        $this->assertEquals([], $_SESSION[$segmentName][SegSession::FLASH_DATA_FOR_NEXT_REQUEST]);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_verify_segment_exists_in_session_and_session_options_were_properly_set_when_session_started_outside_library(): void
    {
        session_start();
        $options = [
            'name' => 'boooooooo',
            'cookie_lifetime' => 8000,
        ];
        $segmentName = 'da-segment';
        $session = new SegSession($segmentName, null, $options);

        // Session was started outside the library.
        $this->assertTrue($session->isStarted());

        // Check that the segment name was properly set in the session object
        $this->assertEquals($segmentName, $session->getSegmentName());

        // Check that session options were not set in the constructor
        // since in this scenario the session was started outside the library.
        $this->assertEquals('PHPSESSID', $session->getName());
        $this->assertNotEquals($options['name'], $session->getName());
        $this->assertNotEquals($options['cookie_lifetime'], ini_get('session.cookie_lifetime'));

        // check that flash was initialized
        $this->assertArrayHasKey($segmentName, $_SESSION);
        $this->assertEquals([SegSession::FLASH_DATA_FOR_NEXT_REQUEST => []], $_SESSION[$segmentName]);
        $this->assertArrayHasKey(SegSession::FLASH_DATA_FOR_NEXT_REQUEST, $_SESSION[$segmentName]);
        $this->assertEquals([], $_SESSION[$segmentName][SegSession::FLASH_DATA_FOR_NEXT_REQUEST]);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_verify_injected_session_object_was_properly_set(): void
    {
        $segmentName = 'da-segment';
        $storage = new Session();
        $session = new SegSession($segmentName, $storage);

        // Injected $storage should be returned by $session->getStorage()
        $this->assertSame($storage, $session->getStorage());
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_verify_injected_session_object_was_properly_set_when_session_started_outside_library(): void
    {
        session_start();
        $segmentName = 'da-segment';
        $storage = new Session();
        $session = new SegSession($segmentName, $storage);

        // Injected $storage should be returned by $session->getStorage()
        $this->assertSame($storage, $session->getStorage());
    }

    /**
     * @runInSeparateProcess
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function test_should_fail_with_empty_segment_name(): void
    {
        $this->expectException(EmptySegmentNameException::class);

        $session = new SegSession('');
    }

    /**
     * @runInSeparateProcess
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function test_should_fail_with_wrong_options(): void
    {
        $this->expectException(WrongSessionOptionException::class);

        $session = new SegSession('a', null, ['foo' => 'bar']);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function test_should_fail_when_headers_sent(): void
    {
        // Because we are not running this in a separate process it will trigger the exception
        $this->expectException(HeadersSentException::class);

        $session = new SegSession('a');
    }

    /**
     * @runInSeparateProcess
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function test_should_fail_when_session_not_startable(): void
    {
        $this->expectException(SessionNotStartedException::class);

        $unstartableSsStorage = new class ('vlah') extends SegSession {
            public function start(array $options = []): bool
            {
                return false;
            }

            public function isStarted(): bool
            {
                return false;
            }
        };

        $session = new SegSession('a', $unstartableSsStorage);
    }
}
