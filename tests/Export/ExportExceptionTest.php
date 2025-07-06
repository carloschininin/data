<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Tests\Export;

use CarlosChininin\Data\Export\ExportException;
use PHPUnit\Framework\TestCase;

class ExportExceptionTest extends TestCase
{
    public function testExportException(): void
    {
        $this->expectException(ExportException::class);
        $this->expectExceptionMessage('Error: Test Exception');

        throw new ExportException('Test Exception');
    }
}
