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

class RemoveFromFlashMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_remove_set_attributes(): void
    {
        $storage = new Session();
        $session = new SegSession('da-segment', $storage);

        $session->setInCurrentFlash('foo-current-1', 'val-in-current-1');
        $session->setInCurrentFlash('foo-current-2', 'val-in-current-2');

        $session->setInNextFlash('foo-next-1', 'val-in-next-1');
        $session->setInNextFlash('foo-next-2', 'val-in-next-2');

        $this->assertTrue($session->hasInCurrentFlash('foo-current-1'));
        $this->assertTrue($session->hasInCurrentFlash('foo-current-2'));
        $this->assertTrue($session->hasInNextFlash('foo-next-1'));
        $this->assertTrue($session->hasInNextFlash('foo-next-2'));

        $session->removeFromFlash('foo-current-1', true);

        $this->assertFalse($session->hasInCurrentFlash('foo-current-1'));
        $this->assertTrue($session->hasInCurrentFlash('foo-current-2'));
        $this->assertTrue($session->hasInNextFlash('foo-next-1'));
        $this->assertTrue($session->hasInNextFlash('foo-next-2'));

        $session->removeFromFlash('foo-current-2', true);

        $this->assertFalse($session->hasInCurrentFlash('foo-current-1'));
        $this->assertFalse($session->hasInCurrentFlash('foo-current-2'));
        $this->assertTrue($session->hasInNextFlash('foo-next-1'));
        $this->assertTrue($session->hasInNextFlash('foo-next-2'));

        $session->removeFromFlash('foo-next-1', true, true);

        $this->assertFalse($session->hasInCurrentFlash('foo-current-1'));
        $this->assertFalse($session->hasInCurrentFlash('foo-current-2'));
        $this->assertFalse($session->hasInNextFlash('foo-next-1'));
        $this->assertTrue($session->hasInNextFlash('foo-next-2'));

        $session->removeFromFlash('foo-next-2', true, true);

        $this->assertFalse($session->hasInCurrentFlash('foo-current-1'));
        $this->assertFalse($session->hasInCurrentFlash('foo-current-2'));
        $this->assertFalse($session->hasInNextFlash('foo-next-1'));
        $this->assertFalse($session->hasInNextFlash('foo-next-2'));
    }
}
