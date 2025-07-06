<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Tests\Export;

use CarlosChininin\Data\Export\ExcelWriter;
use PHPUnit\Framework\TestCase;

class ExcelWriterTest extends TestCase
{
    public function testExcelWriterInstance(): void
    {
        if (!extension_loaded('xlswriter')) {
            $this->markTestSkipped('Xlswriter extension is not loaded.');
        }

        $excelWriter = new ExcelWriter();
        $this->assertInstanceOf(ExcelWriter::class, $excelWriter);
    }
}
