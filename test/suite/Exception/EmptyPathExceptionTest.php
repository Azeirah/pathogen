<?php

/*
 * This file is part of the Pathogen package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Pathogen\Exception;

use Exception;


class EmptyPathExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $exception = new EmptyPathException($previous);

        $this->assertSame(
            "Relative paths must have at least one atom.",
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
