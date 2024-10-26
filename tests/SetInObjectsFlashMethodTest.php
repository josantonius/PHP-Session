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

class SetInObjectsFlashMethodTest extends TestCase
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
        $storage = new Session();
        $session = new SegSession('da-segment', $storage);

        $session->setInObjectsFlash('foo-current-1', 'val-in-current-1');
        $session->setInObjectsFlash('foo-current-2', 'val-in-current-2');

        $this->assertTrue($session->hasInObjectsFlash('foo-current-1'));
        $this->assertTrue($session->hasInObjectsFlash('foo-current-2'));

        $this->assertEquals('val-in-current-1', $session->getFromObjectsFlash('foo-current-1'));
        $this->assertEquals('val-in-current-2', $session->getFromObjectsFlash('foo-current-2'));
    }
}
