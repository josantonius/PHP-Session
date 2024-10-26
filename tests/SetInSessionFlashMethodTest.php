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

class SetInSessionFlashMethodTest extends TestCase
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

        $session->setInSessionFlash('foo-next-1', 'val-in-next-1');
        $session->setInSessionFlash('foo-next-2', 'val-in-next-2');

        $this->assertTrue($session->hasInSessionFlash('foo-next-1'));
        $this->assertTrue($session->hasInSessionFlash('foo-next-2'));

        $this->assertEquals('val-in-next-1', $session->getFromSessionFlash('foo-next-1'));
        $this->assertEquals('val-in-next-2', $session->getFromSessionFlash('foo-next-2'));
    }
}
