<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Tests\Export;

use CarlosChininin\Data\Export\ExportExcel;
use PHPUnit\Framework\TestCase;

class ExportExcelTest extends TestCase
{
    public function testToExcelDate(): void
    {
        $dateTime = new \DateTime('2025-07-06');
        $excelDate = ExportExcel::toExcelDate($dateTime);

        $this->assertSame(45844.20833333333, $excelDate);
    }

    public function testToExcelDateWithNull(): void
    {
        $this->assertNull(ExportExcel::toExcelDate(null));
    }
}
