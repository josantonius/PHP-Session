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

namespace Josantonius\Session\Exceptions;

/**
 * @author https://github.com/rotexdegba
 */
class EmptySegmentNameException extends SessionException
{
    public function __construct(string $methodName)
    {
        parent::__construct(
            $methodName . '(): Session segment name cannot be an empty string.'
        );
    }
}
