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

class SetInCurrentFlashMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_correctly_set_attributes(): void
    {
        $storage = new \Josantonius\Session\Session();
        $session = new SegSession('da-segment', $storage);
        
        $session->setInCurrentFlash('foo-current-1', 'val-in-current-1');
        $session->setInCurrentFlash('foo-current-2', 'val-in-current-2');
        
        $this->assertTrue($session->hasInCurrentFlash('foo-current-1'));
        $this->assertTrue($session->hasInCurrentFlash('foo-current-2'));
        
        $this->assertEquals('val-in-current-1', $session->getFromCurrentFlash('foo-current-1'));
        $this->assertEquals('val-in-current-2', $session->getFromCurrentFlash('foo-current-2'));
    }
}
