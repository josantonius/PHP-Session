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

use Josantonius\Session\Exceptions\SessionNotStartedException;
use Josantonius\Session\Exceptions\EmptySegmentNameException;

/**
 * @author https://github.com/rotexdegba
 *
 * Each valid instance of this class will always have a valid session started or resumed
 */
class FlashableSessionSegment implements SessionInterface
{
    use ExceptionThrowingMethodsTrait;

    /**
     * Used for performing some session operations for each instance of this class
     */
    protected SessionInterface $storage;

    /**
     * Flash data for current request is stored here.
     *
     * It must be copied from
     * $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
     * every time an instance of this class is created.
     *
     * If $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
     * isn't set, this $this->flashDataForCurrentRequest should maintain
     * the default value of empty array.
     */
    protected array $flashDataForCurrentRequest = [];

    /**
     * When different libraries and projects try to modify data with the same keys
     * in $_SESSION , the resulting conflicts can result in unexpected behavior.
     *
     * This value will be used as a key in $_SESSION to avoid session
     * data for an instance of this class from colliding with or being
     * overwritten by other libraries or projects within an application.
     *
     * Be sure to set it to a unique value for each instance of this class.
     * For example, you could use a value such as the fully qualified
     * class name concatenated with the method name of the method where
     * an instance of this class is being created.
     */
    protected string $segmentName;

    /**
     * Key for storing flash data in $_SESSION[$this->segmentName]
     */
    public const FLASH_DATA_FOR_NEXT_REQUEST = self::class . '__flash';

    /**
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
     *
     *
     * @param string $segmentName Name of key to be used in $_SESSION to store all
     *                                    session data for an instance of this class. This
     *                                    helps avoid writing session data directly to
     *                                    $_SESSION, which runs the risk of being overwritten
     *                                    by other libraries that write to $_SESSION with the
     *                                    same key(s). The value specified for this key should
     *                                    be something unique, like the domain name of the web
     *                                    application, or some other unique value that other
     *                                    libraries writing to $_SESSION wouldn't use.
     *
     * @param null|\Josantonius\Session\SessionInterface $storage used for performing some session operations for each instance of this class.
     *                                                            If null, an instance of \Josantonius\Session\Session will be created
     *
     * @param array $options session start configuration options that will be used to
     *                       automatically start a new session or resume existing session
     *                       when an instance of this class is created
     *
     * @throws \Josantonius\Session\Exceptions\EmptySegmentNameException
     * @throws \Josantonius\Session\Exceptions\HeadersSentException
     * @throws \Josantonius\Session\Exceptions\SessionNotStartedException
     * @throws \Josantonius\Session\Exceptions\WrongSessionOptionException
     */
    public function __construct(string $segmentName, ?SessionInterface $storage = null, array $options = [])
    {
        if ($segmentName === '') {
            throw new EmptySegmentNameException(__METHOD__);
        }

        $this->storage = $storage ?? new Session();
        $this->segmentName = $segmentName;

        if (!$this->isStarted()) {
            // start or resume session
            $this->start($options);
        }

        if (!$this->isStarted()) {
            $msg = 'Error: Could not start session in ' . __METHOD__;
            throw new SessionNotStartedException($msg);
        }

        $this->initializeOrReinitialize();
        $this->moveNextFlashToCurrentFlash();
    }

    protected function initializeOrReinitialize(): void
    {
        if ($this->isStarted()) {
            if (!isset($_SESSION[$this->segmentName])) {
                // We want session data to be in a segment to protect it from
                // being overwritten by other packages
                $_SESSION[$this->segmentName] = [];

                if (!isset($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST])) {
                    // Initialize the flash storage in the session's segment
                    $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST] = [];
                }
            }
        }
    }

    protected function moveNextFlashToCurrentFlash(): void
    {
        if ($this->isStarted()) {
            ///////////////////////////////////////////////////////////////////////////
            // Flash update logic: We update the flash data by copying the existing
            // flash data stored in
            // $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
            // to $this->flashDataForCurrentRequest & then reset
            // $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
            // to an empty array.
            //
            // Flash data inside $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
            // will only be accessible in the next request
            //
            // Flash data copied from $_SESSION into $this->flashDataForCurrentRequest from the previous
            // request is flash data that will be accessible in the current request.
            ///////////////////////////////////////////////////////////////////////////
            $this->flashDataForCurrentRequest = $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST] ?? [];

            // Reset the flash data in session to an empty array
            // but we still have the existing flash data in $this->flashDataForCurrentRequest
            $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST] = [];
        }
    }

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
    public function start(array $options = []): bool
    {
        $isStarted = true;

        if (!$this->isStarted()) {
            $isStarted = $this->storage->start($options);

            if ($isStarted) {
                $this->initializeOrReinitialize();
            } // if($isStarted)
        } // if(!$this->isStarted())

        return $isStarted;
    }

    /**
     * Gets all attributes in $_SESSION[$this->segmentName].
     */
    public function all(): array
    {
        return $this->isStarted() ? ($_SESSION[$this->segmentName] ?? []) : [];
    }

    /**
     * Checks if an attribute exists in $_SESSION[$this->segmentName].
     */
    public function has(string $name): bool
    {
        return $this->isStarted() && isset($_SESSION[$this->segmentName][$name]);
    }

    /**
     * Gets an attribute by name from $_SESSION[$this->segmentName].
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->isStarted() ? ($_SESSION[$this->segmentName][$name] ?? $default) : $default;
    }

    /**
     * Sets an attribute by name in $_SESSION[$this->segmentName].
     */
    public function set(string $name, mixed $value): void
    {
        if ($this->isStarted()) {
            // Set it in this session object's segmented session data
            $_SESSION[$this->segmentName][$name] = $value;
        }
    }

    /**
     * Sets several attributes at once inside $_SESSION[$this->segmentName].
     *
     * If attributes exist they are replaced, if they do not exist they are created.
     */
    public function replace(array $data): void
    {
        if ($this->isStarted()) {
            // Replace it in this session object's segmented session data
            $_SESSION[$this->segmentName] =
            array_merge($_SESSION[$this->segmentName], $data);
        }
    }

    /**
     * Deletes an attribute by name from $_SESSION[$this->segmentName] and returns its value.
     *
     * Optionally defines a default value when the attribute does not exist.
     */
    public function pull(string $name, mixed $default = null): mixed
    {
        $value = $default;

        if ($this->isStarted()) {
            // Try to get it from this session object's segmented session data
            // or return the default value
            $value = $_SESSION[$this->segmentName][$name] ?? $default;

            if (isset($_SESSION[$this->segmentName][$name])) {
                // Remove it from this session object's segmented session data
                unset($_SESSION[$this->segmentName][$name]);
            }
        }

        return $value;
    }

    /**
     * Deletes an attribute by name from $_SESSION[$this->segmentName].
     */
    public function remove(string $name): void
    {
        if ($this->isStarted() && isset($_SESSION[$this->segmentName][$name])) {
            // Remove it from this session object's segmented session data
            unset($_SESSION[$this->segmentName][$name]);
        }
    }

    /**
     * Clears the session segment ($_SESSION[$this->segmentName]) and restores
     * this object to its default original state after creation.
     */
    public function clear(): void
    {
        if ($this->isStarted() && isset($_SESSION[$this->segmentName])) {
            // Get rid of this session object's segmented session data, but we
            // don't destroy $_SESSION since other data may be stored in it by
            // other instances of this class or other code in the application
            // this class is being used in.
            unset($_SESSION[$this->segmentName]);

            // Reset segment to empty array
            $_SESSION[$this->segmentName] = [];

            // Reset the flash data in session segment to an empty arrays
            $this->flashDataForCurrentRequest = [];
            $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST] = [];
        }
    }

    /**
     * Gets the session ID.
     */
    public function getId(): string
    {
        return $this->storage->getId();
    }

    /**
     * Sets the session ID.
     *
     * This method will be of no effect if called outside the constructor
     * of this class since the session will already be started after the
     * constructor method finishes execution
     */
    public function setId(string $sessionId): void
    {
        (!$this->storage->isStarted()) && $this->storage->setId($sessionId);
    }

    /**
     * Updates the current session id with a newly generated one.
     */
    public function regenerateId(bool $deleteOldSession = false): bool
    {
        return $this->isStarted() && $this->storage->regenerateId($deleteOldSession);
    }

    /**
     * Gets the session name.
     */
    public function getName(): string
    {
        return $this->storage->getName();
    }

    /**
     * Sets the session name.
     *
     * This method will be of no effect if called outside the constructor
     * of this class since the session will already be started after the
     * constructor method finishes execution
     */
    public function setName(string $name): void
    {
        (!$this->storage->isStarted()) && $this->storage->setName($name);
    }

    /**
     * Clears the session segment ($_SESSION[$this->segmentName]) and restores
     * this object to its original state after creation.
     *
     * The session ($_SESSION) is not destroyed.
     */
    public function destroy(): bool
    {
        $this->clear();

        return true;
    }

    /**
     * Checks if the session is started.
     */
    public function isStarted(): bool
    {
        return $this->storage->isStarted();
    }

    /**
     * Sets an item with the specified $key in the flash storage for an instance
     * of this class (i.e. $this->flashDataForCurrentRequest).
     *
     * The item will only be retrievable from the instance of this class it was set in.
     */
    public function setInCurrentFlash(string $key, mixed $value): void
    {
        $this->flashDataForCurrentRequest[$key] = $value;
    }

    /**
     * Sets an item with the specified $key in the flash storage located in
     * $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
     *
     * The item will only be retrievable by calling the getFromCurrentFlash
     * on the next instance of this class created with the same segment name.
     */
    public function setInNextFlash(string $key, mixed $value): void
    {
        $canSet = $this->isStarted()
            && isset($_SESSION[$this->segmentName])
            && isset($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]);

        if ($canSet) {
            // Set it in this session object's segmented session data
            $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST][$key] = $value;
        }
    }

    /**
     * Check if item with specified $key exists in the flash storage for
     * an instance of this class (i.e. in $this->flashDataForCurrentRequest).
     */
    public function hasInCurrentFlash(string $key): bool
    {
        ////////////////////////////////////////////////////////////////////////
        // Accessible flash data for current request is always inside
        // $this->flashDataForCurrentRequest
        // while
        // $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
        // contains flash data for the next request
        ////////////////////////////////////////////////////////////////////////
        return \array_key_exists($key, $this->flashDataForCurrentRequest);
    }

    /**
     * Check if item with specified $key exists in
     * $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST].
     */
    public function hasInNextFlash(string $key): bool
    {
        ////////////////////////////////////////////////////////////////////////
        // Accessible flash data for current request is always inside
        // $this->flashDataForCurrentRequest
        // while
        // $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
        // contains flash data for the next request
        ////////////////////////////////////////////////////////////////////////
        return
            ( // Check in this session object's segmented session data
                $this->isStarted()
                && isset($_SESSION[$this->segmentName])
                && isset($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST])
                && is_array($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST])
                && isset($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST][$key])
            );
    }

    /**
     * Get an item with the specified $key from the flash storage for an instance
     * of this class (i.e. $this->flashDataForCurrentRequest) if it exists or return $default.
     */
    public function getFromCurrentFlash(string $key, mixed $default = null): mixed
    {
        ////////////////////////////////////////////////////////////////////////
        // Accessible flash data for current request is always inside
        // $this->flashDataForCurrentRequest
        // while
        // $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
        // contains flash data for the next request
        ////////////////////////////////////////////////////////////////////////
        return ($this->isStarted() &&  $this->hasInCurrentFlash($key)) ? $this->flashDataForCurrentRequest[$key] : $default;
    }

    /**
     * Get an item with the specified $key from
     *
     * $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
     * if it exists or return $default.
     */
    public function getFromNextFlash(string $key, mixed $default = null): mixed
    {
        return ($this->isStarted() && $this->hasInNextFlash($key))
                ? $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST][$key]
                : $default;
    }

    /**
     * Remove an item with the specified $key (if it exists) from:
     * - the flash storage for an instance of this class (i.e. in $this->flashDataForCurrentRequest), if $for_current_request === true
     * - $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST], if $for_next_request === true
     */
    public function removeFromFlash(
        string $key,
        bool $for_current_request = true,
        bool $for_next_request = false
    ): void {
        ////////////////////////////////////////////////////////////////////////
        // Accessible flash data for current request is always inside
        // $this->flashDataForCurrentRequest
        // while
        // $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
        // contains flash data for the next request
        ////////////////////////////////////////////////////////////////////////
        if ($for_current_request && $this->hasInCurrentFlash($key)) {
            unset($this->flashDataForCurrentRequest[$key]);
        }

        if ($for_next_request && $this->isStarted() && $this->hasInNextFlash($key)) {
            // Remove it from this session object's segmented session data
            unset($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST][$key]);
        } // if($for_next_request)
    }

    /**
     * Get all items in the flash storage for an instance of this class (i.e. in $this->flashDataForCurrentRequest)
     */
    public function getAllFromCurrentFlash(): array
    {
        return $this->flashDataForCurrentRequest;
    }

    /**
     * Get all items in $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
     */
    public function getAllFromNextFlash(): array
    {
        $canGet = $this->isStarted()
            && isset($_SESSION[$this->segmentName])
            && isset($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST])
            && is_array($_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]);

        if ($canGet) {
            // Get it from this session object's segmented session data
            return $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST];
        }

        return [];
    }

    public function getSegmentName(): string
    {
        return $this->segmentName;
    }

    public function getStorage(): SessionInterface
    {
        return $this->storage;
    }
}
