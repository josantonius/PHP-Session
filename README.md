# PHP Session library

[![Latest Stable Version](https://poser.pugx.org/josantonius/session/v/stable)](https://packagist.org/packages/josantonius/session)
[![License](https://poser.pugx.org/josantonius/session/license)](LICENSE)
[![Total Downloads](https://poser.pugx.org/josantonius/session/downloads)](https://packagist.org/packages/josantonius/session)
[![CI](https://github.com/josantonius/php-session/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/josantonius/php-session/actions/workflows/ci.yml)
[![CodeCov](https://codecov.io/gh/josantonius/php-session/branch/main/graph/badge.svg)](https://codecov.io/gh/josantonius/php-session)
[![PSR1](https://img.shields.io/badge/PSR-1-f57046.svg)](https://www.php-fig.org/psr/psr-1/)
[![PSR4](https://img.shields.io/badge/PSR-4-9b59b6.svg)](https://www.php-fig.org/psr/psr-4/)
[![PSR12](https://img.shields.io/badge/PSR-12-1abc9c.svg)](https://www.php-fig.org/psr/psr-12/)

**Translations**: [Español](.github/lang/es-ES/README.md)

PHP library for handling sessions.

---

- [Requirements](#requirements)
- [Installation](#installation)
- [Available Classes](#available-classes)
  - [Session Class](#session-class)
  - [Session Facade](#session-facade)
- [Exceptions Used](#exceptions-used)
- [Usage](#usage)
- [Tests](#tests)
- [TODO](#todo)
- [Changelog](#changelog)
- [Contribution](#contribution)
- [Sponsor](#sponsor)
- [License](#license)

---

## Requirements

- Operating System: Linux | Windows.

- PHP versions: 8.0 | 8.1 | 8.2 | 8.3.

## Installation

The preferred way to install this extension is through [Composer](http://getcomposer.org/download/).

To install **PHP Session library**, simply:

```console
composer require josantonius/session
```

The previous command will only install the necessary files,
if you prefer to **download the entire source code** you can use:

```console
composer require josantonius/session --prefer-source
```

You can also **clone the complete repository** with Git:

```console
git clone https://github.com/josantonius/php-session.git
```

## Available Classes

### Session Class

`Josantonius\Session\Session`

Starts the session:

```php
/**
 * @throws HeadersSentException        if headers already sent.
 * @throws SessionStartedException     if session already started.
 * @throws WrongSessionOptionException if setting options failed.
 * 
 * @see https://php.net/session.configuration for List of available $options.
 */
public function start(array $options = []): bool;
```

Check if the session is started:

```php
public function isStarted(): bool;
```

Sets an attribute by name:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public function set(string $name, mixed $value): void;
```

Gets an attribute by name:

```php
/**
 * Optionally defines a default value when the attribute does not exist.
 */
public function get(string $name, mixed $default = null): mixed;
```

Gets all attributes:

```php
public function all(): array;
```

Check if an attribute exists in the session:

```php
public function has(string $name): bool;
```

Sets several attributes at once:

```php
/**
 * If attributes exist they are replaced, if they do not exist they are created.
 * 
 * @throws SessionNotStartedException if session was not started.
 */
public function replace(array $data): void;
```

Deletes an attribute by name and returns its value:

```php
/**
 * Optionally defines a default value when the attribute does not exist.
 * 
 * @throws SessionNotStartedException if session was not started.
 */
public function pull(string $name, mixed $default = null): mixed;
```

Deletes an attribute by name:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public function remove(string $name): void;
```

Free all session variables:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public function clear(): void;
```

Gets the session ID:

```php
public function getId(): string;
```

Sets the session ID:

```php
/**
 * @throws SessionStartedException if session already started.
 */
public function setId(string $sessionId): void;
```

Update the current session ID with a newly generated one:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public function regenerateId(bool $deleteOldSession = false): bool;
```

Gets the session name:

```php
public function getName(): string;
```

Sets the session name:

```php
/**
 * @throws SessionStartedException if session already started.
 */
public function setName(string $name): void;
```

Destroys the session:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public function destroy(): bool;
```

### Session Facade

`Josantonius\Session\Facades\Session`

Starts the session:

```php
/**
 * @throws HeadersSentException        if headers already sent.
 * @throws SessionStartedException     if session already started.
 * @throws WrongSessionOptionException if setting options failed.
 * 
 * @see https://php.net/session.configuration for List of available $options.
 */
public static function start(array $options = []): bool;
```

Check if the session is started:

```php
public static function isStarted(): bool;
```

Sets an attribute by name:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public static function set(string $name, mixed $value): void;
```

Gets an attribute by name:

```php
/**
 * Optionally defines a default value when the attribute does not exist.
 */
public static function get(string $name, mixed $default = null): mixed;
```

Gets all attributes:

```php
public static function all(): array;
```

Check if an attribute exists in the session:

```php
public static function has(string $name): bool;
```

Sets several attributes at once:

```php
/**
 * If attributes exist they are replaced, if they do not exist they are created.
 * 
 * @throws SessionNotStartedException if session was not started.
 */
public static function replace(array $data): void;
```

Deletes an attribute by name and returns its value:

```php
/**
 * Optionally defines a default value when the attribute does not exist.
 * 
 * @throws SessionNotStartedException if session was not started.
 */
public static function pull(string $name, mixed $default = null): mixed;
```

Deletes an attribute by name:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public static function remove(string $name): void;
```

Free all session variables:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public static function clear(): void;
```

Gets the session ID:

```php
public static function getId(): string;
```

Sets the session ID:

```php
/**
 * @throws SessionStartedException if session already started.
 */
public static function setId(string $sessionId): void;
```

Update the current session ID with a newly generated one:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public static function regenerateId(bool $deleteOldSession = false): bool;
```

Gets the session name:

```php
public static function getName(): string;
```

Sets the session name:

```php
/**
 * @throws SessionStartedException if session already started.
 */
public static function setName(string $name): void;
```

Destroys the session:

```php
/**
 * @throws SessionNotStartedException if session was not started.
 */
public static function destroy(): bool;
```


### Flashable Session Segment Class

`Josantonius\Session\FlashableSessionSegment`

This is a special type of Session class whose data is always stored in a sub-array in **$_SESSION**. This sub-array is automatically created & managed by each instance of this class and its key in **$_SESSION** is the string value passed as the first argument to this class' constructor. The session is always either auto-started or auto-resumed when an instance of this class is created, so you never need to explicitly call the **start** method after creating an instance of this class. This class also has flash functionality that allows you to store values in session via an instance of this class that are meant to be read once from session and removed after that one-time read (for example, you can set a login success notification message in session in your application using this flash mechanism and once the success message is read once from the session, it will no longer be available in session). You can also store non-flash values in session via each instance of this class that will stay in session until you explicitly remove them or the session expires or is destroyed. Both flash and non-flash values are stored in the earlier mentioned sub-array in **$_SESSION** and as a result, they are shielded from being overwritten by other parts of your application that write to **$_SESSION** directly as long as the key / segment name you specified for each instance of this class is unique & not being already used to store some other stuff in **$_SESSION**.

The first non-optional argument to its constructor is a key (also referred to as a segment name) to the sub-array  in **$_SESSION** where all the data for the instance of `Josantonius\Session\FlashableSessionSegment` you are creating will be stored. Make sure the key you specify is a unique key that isn't being used by other packages in your application that write to **$_SESSION** so you don't have your data stored via an instance of this class overwritten by some other packages in your application.

An instance of **Josantonius\Session\SessionInterface** can optionally be passed as the second argument to the constructor.
This instance will be used to interact with the session (**$_SESSION**). If the second argument is **null**
then an instance of **Josantonius\Session\Session** will be created & used to interact with the session (**$_SESSION**).

The optional third argument to its constructor is an array of options for configuring the session. It must contain the same valid options acceptable by [session_start](https://www.php.net/manual/en/function.session-start.php).


Creates an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
/**
 * @throws EmptySegmentNameException   if an empty string (i.e. '' or "") was passed as the first argument
 * @throws HeadersSentException        if headers already sent.
 * @throws SessionNotStartedException  if session could not be started.
 * @throws WrongSessionOptionException if setting options failed.
 * 
 * @param string $segmentName key to be used in $_SESSION to store all the data for an instance of this class

 * @param null|\Josantonius\Session\SessionInterface $storage used for performing some session operations for each instance of this class. If null, an instance of \Josantonius\Session\Session will be created & used.
 * 
 * @param array $options session start configuration options that will be used to automatically start a new session or resume an existing session. It must contain the same valid options acceptable by https://www.php.net/manual/en/function.session-start.php.
 * 
 * @see https://php.net/session.configuration for List of available $options.
 */
public function __construct(string $segmentName, ?SessionInterface $storage = null, array $options = []);
```

Get the segment name (this is the key to the sub-array inside **$_SESSION** where all the data for an instance of `Josantonius\Session\FlashableSessionSegment` will be stored. It is the first argument passed to the constructor above):

```php
public function getSegmentName(): string;
```

Get the instance of `\Josantonius\Session\SessionInterface` being used to perform session operations on behalf of an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
public function getStorage(): SessionInterface;
```

Starts the session:

```php
public function start(array $options = []): bool
```

Check if the session is started:

```php
public function isStarted(): bool;
```

Gets all values stored via an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
public function all(): array
```

Checks if an attribute exists via an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
public function has(string $name): bool
```

Get the corresponding value associated with an attribute that was stored via an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
/**
 * Optionally defines a default value when the attribute does not exist.
 */
public function get(string $name, mixed $default = null): mixed
```

Sets an attribute and its corresponding value via an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
public function set(string $name, mixed $value): void
```

Sets several attribute & value pairs at once via an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
/**
 * If attributes exist they are replaced, if they do not exist they are created.
 */
public function replace(array $data): void
```

Deletes an attribute associated with an instance of `Josantonius\Session\FlashableSessionSegment` by name and returns its value:

```php
/**
 * Optionally defines a default value when the attribute does not exist.
 */
public function pull(string $name, mixed $default = null): mixed
```

Deletes an attribute associated with an instance of `Josantonius\Session\FlashableSessionSegment` by name:

```php
public function remove(string $name): void
```

Clears / frees all attributes and corresponding values stored via an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
public function clear(): void
```

Gets the session ID:

```php
public function getId(): string
```

Sets the session ID:

```php
/**
 * This method will be of no effect if called outside the constructor
 * of this class since the session will already be started after the
 * constructor method finishes execution
 */
public function setId(string $sessionId): void
```

Update the current session ID with a newly generated one:

```php
public function regenerateId(bool $deleteOldSession = false): bool
```

Gets the session name:

```php
public function getName(): string;
```

Sets the session name:

```php
/**
 * This method will be of no effect if called outside the constructor
 * of this class since the session will already be started after the
 * constructor method finishes execution.
 */
public function setName(string $name): void;
```

Clears / frees all attributes and corresponding values stored via an instance of `Josantonius\Session\FlashableSessionSegment`, but does not destroy the session **$_SESSION**. **$_SESSION** data written outside this class will remain intact:

```php
/**
 * Always returns true
 */
public function destroy(): bool;
```


> **Flash Mechanism:** every time an instance of `Josantonius\Session\FlashableSessionSegment` is created, an array to store flash data (meant to be read only once by the next instance of `Josantonius\Session\FlashableSessionSegment` with the same key / segment name) is either created inside the sub-array inside **$_SESSION** where all the data for the created instance of `Josantonius\Session\FlashableSessionSegment` will be stored or if that sub-sub-array for the flash data already exists in **$_SESSION**, it is copied into the **objectsFlashData** property of the created instance of `Josantonius\Session\FlashableSessionSegment` and reset to an empty array in **$_SESSION**. 

> Flash data contained in the **objectsFlashData** property of an instance of `Josantonius\Session\FlashableSessionSegment` is referred to as the **current / object instance's flash data** (which is the copy of flash data read once from the session). Flash data set inside the sub-array in **$_SESSION** is referred to as the **next / session flash data** (i.e. flash data to be copied from **$_SESSION** into the **objectsFlashData** property of the next instance of `Josantonius\Session\FlashableSessionSegment` with the same key / segment name as the current instance used to store the flash data in **$_SESSION**).

> Below are methods related to storing and retrieving flash data. More concrete examples of how to store and retrieve flash data will be shown in the usage section later below.

Set an attribute & value in the object instance's flash for an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
/**
 * Sets an item with the specified $key in the flash storage for an instance
 * of this class (i.e. $this->objectsFlashData).
 *
 * The item will only be retrievable from the instance of this class it was set in.
 */
public function setInObjectsFlash(string $key, mixed $value): void
```

Set an attribute & value in the session flash for an instance of `Josantonius\Session\FlashableSessionSegment`:

```php
/**
 * Sets an item with the specified $key in the flash storage located in
 * $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
 *
 * The item will only be retrievable by calling the getFromObjectsFlash
 * on the next instance of this class created with the same segment name.
 */
public function setInSessionFlash(string $key, mixed $value): void
```

Check if an attribute exists in the object instance's flash:

```php
/**
 * Check if item with specified $key exists in the flash storage for
 * an instance of this class (i.e. in $this->objectsFlashData).
 */
public function hasInObjectsFlash(string $key): bool
```


Check if an attribute exists in the session flash:

```php
/**
 * Check if item with specified $key exists in
 * $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST].
 */
public function hasInSessionFlash(string $key): bool
```

Get the value associated with a specified attribute from the object instance's flash:

```php
/**
 * Get an item with the specified $key from the flash storage for an instance
 * of this class (i.e. $this->objectsFlashData) if it exists or return $default.
 */
public function getFromObjectsFlash(string $key, mixed $default = null): mixed
```


Get the value associated with a specified attribute from the session flash:

```php
/**
 * Get an item with the specified $key from
 *
 * $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
 * if it exists or return $default.
 */
public function getFromSessionFlash(string $key, mixed $default = null): mixed
```

Remove an attribute & its corresponding value from the object instance's and / or session flash:

```php
/**
 * Remove an item with the specified $key (if it exists) from:
 * - the flash storage for an instance of this class (i.e. in $this->objectsFlashData),
 * if $fromObjectsFlash === true
 * - $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST],
 * if $fromSessionFlash === true
 */
public function removeFromFlash(
    string $key,
    bool $fromObjectsFlash = true,
    bool $fromSessionFlash = false
): void 
```

Get all attributes & their corresponding values from the object instance's flash:

```php
/**
 * Get all items in the flash storage for an instance of this class 
 * (i.e. in $this->objectsFlashData)
 */
public function getAllFromObjectsFlash(): array
```

Get all attributes & their corresponding values from the session flash:

```php
/**
 * Get all items in $_SESSION[$this->segmentName][static::FLASH_DATA_FOR_NEXT_REQUEST]
 */
public function getAllFromSessionFlash(): array
```

## Exceptions Used in \Josantonius\Session\FlashableSessionSegment

```php
use Josantonius\Session\Exceptions\EmptySegmentNameException;
use Josantonius\Session\Exceptions\SessionNotStartedException;
```

## Usage

Example of use for this library:

### Starts the session without setting options

```php
use Josantonius\Session\Session;

$session = new Session();

$session->start();
```

```php
use Josantonius\Session\Facades\Session;

Session::start();
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');
```


### Starts the session setting options

```php
use Josantonius\Session\Session;

$session = new Session();

$session->start([
    // 'cache_expire' => 180,
    // 'cache_limiter' => 'nocache',
    // 'cookie_domain' => '',
    'cookie_httponly' => true,
    'cookie_lifetime' => 8000,
    // 'cookie_path' => '/',
    'cookie_samesite' => 'Strict',
    'cookie_secure'   => true,
    // 'gc_divisor' => 100,
    // 'gc_maxlifetime' => 1440,
    // 'gc_probability' => true,
    // 'lazy_write' => true,
    // 'name' => 'PHPSESSID',
    // 'read_and_close' => false,
    // 'referer_check' => '',
    // 'save_handler' => 'files',
    // 'save_path' => '',
    // 'serialize_handler' => 'php',
    // 'sid_bits_per_character' => 4,
    // 'sid_length' => 32,
    // 'trans_sid_hosts' => $_SERVER['HTTP_HOST'],
    // 'trans_sid_tags' => 'a=href,area=href,frame=src,form=',
    // 'use_cookies' => true,
    // 'use_only_cookies' => true,
    // 'use_strict_mode' => false,
    // 'use_trans_sid' => false,
]);
```

```php
use Josantonius\Session\Facades\Session;

Session::start([
    'cookie_httponly' => true,
]);
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment(
    'demo-segment', 
    null, 
    ['cookie_httponly' => true]
);
```

### Check if the session is started

```php
use Josantonius\Session\Session;

$session = new Session();

$session->isStarted();
```

```php
use Josantonius\Session\Facades\Session;

Session::isStarted();
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->isStarted();
```

### Sets an attribute by name

```php
use Josantonius\Session\Session;

$session = new Session();

$session->set('foo', 'bar');
```

```php
use Josantonius\Session\Facades\Session;

Session::set('foo', 'bar');
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->set('foo', 'bar');
```

### Gets an attribute by name without setting a default value

```php
use Josantonius\Session\Session;

$session = new Session();

$session->get('foo'); // null if attribute does not exist
```

```php
use Josantonius\Session\Facades\Session;

Session::get('foo'); // null if attribute does not exist
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->get('foo'); // null if attribute does not exist
```

### Gets an attribute by name setting a default value

```php
use Josantonius\Session\Session;

$session = new Session();

$session->get('foo', false); // false if attribute does not exist
```

```php
use Josantonius\Session\Facades\Session;

Session::get('foo', false); // false if attribute does not exist
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->get('foo', false); // false if attribute does not exist
```

### Gets all attributes

```php
use Josantonius\Session\Session;

$session = new Session();

$session->all();
```

```php
use Josantonius\Session\Facades\Session;

Session::all();
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->all();
```

### Check if an attribute exists in the session

```php
use Josantonius\Session\Session;

$session = new Session();

$session->has('foo');
```

```php
use Josantonius\Session\Facades\Session;

Session::has('foo');
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->has('foo');
```

### Sets several attributes at once

```php
use Josantonius\Session\Session;

$session = new Session();

$session->replace(['foo' => 'bar', 'bar' => 'foo']);
```

```php
use Josantonius\Session\Facades\Session;

Session::replace(['foo' => 'bar', 'bar' => 'foo']);
```


```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->replace(['foo' => 'bar', 'bar' => 'foo']);
```

### Deletes an attribute and returns its value or the default value if not exist

```php
use Josantonius\Session\Session;

$session = new Session();

$session->pull('foo'); // null if attribute does not exist
```

```php
use Josantonius\Session\Facades\Session;

Session::pull('foo'); // null if attribute does not exist
```


```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->pull('foo'); // null if attribute does not exist
```

### Deletes an attribute and returns its value or the custom value if not exist

```php
use Josantonius\Session\Session;

$session = new Session();

$session->pull('foo', false); // false if attribute does not exist
```

```php
use Josantonius\Session\Facades\Session;

Session::pull('foo', false); // false if attribute does not exist
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->pull('foo', false); // false if attribute does not exist
```

### Deletes an attribute by name

```php
use Josantonius\Session\Session;

$session = new Session();

$session->remove('foo');
```

```php
use Josantonius\Session\Facades\Session;

Session::remove('foo');
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->remove('foo');
```

### Free all session variables

```php
use Josantonius\Session\Session;

$session = new Session();

$session->clear();
```

```php
use Josantonius\Session\Facades\Session;

Session::clear();
```

### Free all session segment variables

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

// Only removes flash and non-flash data from the segment's 
// sub-array in $_SESSION
$sessionSegment->clear();
```

### Gets the session ID

```php
use Josantonius\Session\Session;

$session = new Session();

$session->getId();
```

```php
use Josantonius\Session\Facades\Session;

Session::getId();
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->getId();
```

### Sets the session ID

```php
use Josantonius\Session\Session;

$session = new Session();

$session->setId('foo');
```

```php
use Josantonius\Session\Facades\Session;

Session::setId('foo');
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

// Does nothing because sessions are auto-started for each
// instance of \Josantonius\Session\FlashableSessionSegment.
// Setting the ID after the session has already been started
// is of no effect.
$sessionSegment->setId('foo');
```

### Update the current session ID with a newly generated one

```php
use Josantonius\Session\Session;

$session = new Session();

$session->regenerateId();
```

```php
use Josantonius\Session\Facades\Session;

Session::regenerateId();
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->regenerateId();
```

### Update the current session ID with a newly generated one deleting the old session

```php
use Josantonius\Session\Session;

$session = new Session();

$session->regenerateId(true);
```

```php
use Josantonius\Session\Facades\Session;

Session::regenerateId(true);
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->regenerateId(true);
```

### Gets the session name

```php
use Josantonius\Session\Session;

$session = new Session();

$session->getName();
```

```php
use Josantonius\Session\Facades\Session;

Session::getName();
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

$sessionSegment->getName();
```

### Sets the session name

```php
use Josantonius\Session\Session;

$session = new Session();

$session->setName('foo');
```

```php
use Josantonius\Session\Facades\Session;

Session::setName('foo');
```

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

// Does nothing because sessions are auto-started for each
// instance of \Josantonius\Session\FlashableSessionSegment.
// Setting the session name after the session has already 
// been started is of no effect.
$sessionSegment->setName('foo');
```

### Destroys the session

```php
use Josantonius\Session\Session;

$session = new Session();

$session->destroy();
```

```php
use Josantonius\Session\Facades\Session;

Session::destroy();
```

### Destroy the session segment but not the session

```php
use Josantonius\Session\FlashableSessionSegment;

/////////////////////////////////////////////////
// Auto-start new session & initialize sub-array
// inside $_SESSION to store this objects data
// in $_SESSION & initialize session flash data
// to an empty array
// 
// OR
// 
// auto-resume existing session & read flash
// data in $_SESSION (if any) into this object's 
// objectsFlashData property & reset the flash 
// data in $_SESSION to an empty array
/////////////////////////////////////////////////
$sessionSegment = new FlashableSessionSegment('demo-segment');

// Only removes flash and non-flash data from the segment's 
// sub-array in $_SESSION
$sessionSegment->destroy();
```

## Usage of Flash Functionality

The major use case of flashes is to store data that is only meant to be read from the session flash storage once and only once and immediately it is read once, it is automatically removed from the session flash storage.

For example you may want to store a message indicating the status of an operation (like whether or not you were able to successfully delete a record from the database) in the the flash storage, which you intend to be read only once when another script runs that tries to read that data. Let's show how you can accomplish that between two scripts: **script-a.php** and **script-b.php**

### script-a.php

```php
use Josantonius\Session\FlashableSessionSegment;

// perform some operations
// ......
// .....

$operationStatusMessage = "Operation Successful";

$sessionSegment = new FlashableSessionSegment('demo-segment');



```


## Tests

To run [tests](tests) you just need [composer](http://getcomposer.org/download/)
and to execute the following:

```console
git clone https://github.com/josantonius/php-session.git
```

```console
cd php-session
```

```console
composer install
```

Run unit tests with [PHPUnit](https://phpunit.de/):

```console
composer phpunit
```

Run code standard tests with [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

```console
composer phpcs
```

Run [PHP Mess Detector](https://phpmd.org/) tests to detect inconsistencies in code style:

```console
composer phpmd
```

Run all previous tests:

```console
composer tests
```

## TODO

- [ ] Add new feature
- [ ] Improve tests
- [ ] Improve documentation
- [ ] Improve English translation in the README file
- [ ] Refactor code for disabled code style rules (see phpmd.xml and phpcs.xml)
- [ ] Show an example of renewing the session lifetime
- [ ] Feature to enable/disable exceptions?
- [ ] Feature to add prefixes in session attributes?

## Changelog

Detailed changes for each release are documented in the
[release notes](https://github.com/josantonius/php-session/releases).

## Contribution

Please make sure to read the [Contributing Guide](.github/CONTRIBUTING.md), before making a pull
request, start a discussion or report a issue.

Thanks to all [contributors](https://github.com/josantonius/php-session/graphs/contributors)! :heart:

## Sponsor

If this project helps you to reduce your development time,
[you can sponsor me](https://github.com/josantonius#sponsor) to support my open source work :blush:

## License

This repository is licensed under the [MIT License](LICENSE).

Copyright © 2017-present, [Josantonius](https://github.com/josantonius#contact)
