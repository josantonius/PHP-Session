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

class GetAllFromObjectsFlashMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_empty_array_on_newly_created_segment(): void
    {
        $session = new SegSession('da-segment');

        $this->assertEquals([], $session->getAllFromObjectsFlash());
    }

    /**
     * @runInSeparateProcess
     */
    public function test_should_return_right_values_before_and_after_setting_items_in_current_flash(): void
    {
        $session = new SegSession('da-segment');

        $session->setInObjectsFlash('foo', 'bar');
        $session->setInObjectsFlash('bar', 'foo');

        $this->assertEquals(
            ['foo' => 'bar', 'bar' => 'foo'],
            $session->getAllFromObjectsFlash()
        );

        // these values should be available in the current flash of the next instance below
        $session->setInSessionFlash('foo2', 'bar2');
        $session->setInSessionFlash('foo3', 'bar3');

        $session2 = new SegSession('da-segment');

        // previous next flash items should now be in the current flash for this instance
        $this->assertEquals(
            ['foo2' => 'bar2', 'foo3' => 'bar3'],
            $session2->getAllFromObjectsFlash()
        );
    }
}
