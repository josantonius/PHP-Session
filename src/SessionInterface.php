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

/**
 * Description of SessionInterface
 *
 * @author https://github.com/rotexdegba
 */
interface SessionInterface
{
    /**
     * Starts the session.
     *
     * List of available $options with their default values:
     *
     * * cache_expire: "180"
     * * cache_limiter: "nocache"
     * * cookie_domain: ""
     * * cookie_httponly: "0"
     * * cookie_lifetime: "0"
     * * cookie_path: "/"
     * * cookie_samesite: ""
     * * cookie_secure: "0"
     * * gc_divisor: "100"
     * * gc_maxlifetime: "1440"
     * * gc_probability: "1"
     * * lazy_write: "1"
     * * name: "PHPSESSID"
     * * read_and_close: "0"
     * * referer_check: ""
     * * save_handler: "files"
     * * save_path: ""
     * * serialize_handler: "php"
     * * sid_bits_per_character: "4"
     * * sid_length: "32"
     * * trans_sid_hosts: $_SERVER['HTTP_HOST']
     * * trans_sid_tags: "a=href,area=href,frame=src,form="
     * * use_cookies: "1"
     * * use_only_cookies: "1"
     * * use_strict_mode: "0"
     * * use_trans_sid: "0"
     *
     * @see https://php.net/session.configuration
     */
    public function start(array $options = []): bool;

    /**
     * Gets all attributes.
     */
    public function all(): array;

    /**
     * Checks if an attribute exists in the session.
     */
    public function has(string $name): bool;

    /**
     * Gets an attribute by name.
     *
     * Optionally defines a default value when the attribute does not exist.
     */
    public function get(string $name, mixed $default = null): mixed;

    /**
     * Sets an attribute by name.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    public function set(string $name, mixed $value): void;

    /**
     * Sets several attributes at once.
     *
     * If attributes exist they are replaced, if they do not exist they are created.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    public function replace(array $data): void;

    /**
     * Deletes an attribute by name and returns its value.
     *
     * Optionally defines a default value when the attribute does not exist.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    public function pull(string $name, mixed $default = null): mixed;

    /**
     * Deletes an attribute by name.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    public function remove(string $name): void;

    /**
     * Free all session variables or all session segment variables.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    public function clear(): void;

    /**
     * Gets the session ID.
     */
    public function getId(): string;

    /**
     * Sets the session ID.
     *
     * @throws SessionStartedException if session already started.
     */
    public function setId(string $sessionId): void;

    /**
     * Updates the current session id with a newly generated one.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    public function regenerateId(bool $deleteOldSession = false): bool;

    /**
     * Gets the session name.
     */
    public function getName(): string;

    /**
     * Sets the session name.
     *
     * @throws SessionStartedException if session already started.
     */
    public function setName(string $name): void;

    /**
     * Destroys the session or session segment.
     *
     * @throws SessionNotStartedException if session was not started.
     */
    public function destroy(): bool;

    /**
     * Checks if the session is started.
     */
    public function isStarted(): bool;
}
