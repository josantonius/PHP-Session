<?php

declare(strict_types=1);

/*
 * This file is part of https://github.com/josantonius/php-session repository.
 *
 * (c) Josantonius <hello@josantonius.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Josantonius\Session;

use Josantonius\Session\Exceptions\HeadersSentException;
use Josantonius\Session\Exceptions\SessionStartedException;
use Josantonius\Session\Exceptions\SessionNotStartedException;
use Josantonius\Session\Exceptions\WrongSessionOptionException;

/**
 * @author https://github.com/rotexdegba
 */
trait ExceptionThrowingMethodsTrait
{
    /**
     * Throw exception if the session have wrong options.
     *
     * @throws WrongSessionOptionException If setting options failed.
     */
    private function throwExceptionIfHasWrongOptions(array $options): void
    {
        $validOptions = array_flip([
            'cache_expire',    'cache_limiter',     'cookie_domain',          'cookie_httponly',
            'cookie_lifetime', 'cookie_path',       'cookie_samesite',        'cookie_secure',
            'gc_divisor',      'gc_maxlifetime',    'gc_probability',         'lazy_write',
            'name',            'read_and_close',    'referer_check',          'save_handler',
            'save_path',       'serialize_handler', 'sid_bits_per_character', 'sid_length',
            'trans_sid_hosts', 'trans_sid_tags',    'use_cookies',            'use_only_cookies',
            'use_strict_mode', 'use_trans_sid',
        ]);

        foreach (array_keys($options) as $key) {
            if (!isset($validOptions[$key])) {
                throw new WrongSessionOptionException($key);
            }
        }
    }

    /**
     * Throw exception if headers have already been sent.
     *
     * @throws HeadersSentException if headers already sent.
     */
    private function throwExceptionIfHeadersWereSent(): void
    {
        $headersWereSent = (bool) ini_get('session.use_cookies') && headers_sent($file, $line);

        $headersWereSent && throw new HeadersSentException($file, $line);
    }

    /**
     * Throw exception if the session has already been started.
     *
     * @throws SessionStartedException if session already started.
     */
    private function throwExceptionIfSessionWasStarted(): void
    {
        $methodName = debug_backtrace()[1]['function'] ?? 'unknown';

        $this->isStarted() && throw new SessionStartedException($methodName);
    }

    /**
     * Throw exception if the session was not started.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    private function throwExceptionIfSessionWasNotStarted(): void
    {
        $methodName = debug_backtrace()[1]['function'] ?? 'unknown';

        !$this->isStarted() && throw new SessionNotStartedException($methodName);
    }
}
