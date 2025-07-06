<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Tests\Export;

use CarlosChininin\Data\Export\ExportData;
use PHPUnit\Framework\TestCase;

class ExportDataTest extends TestCase
{
    private ExportData $exportData;

    protected function setUp(): void
    {
        $this->exportData = new ExportData();
    }

    public function testColumnLabel(): void
    {
        $this->assertSame('A', $this->exportData->columnLabel(ord('A')));
        $this->assertSame('Z', $this->exportData->columnLabel(ord('Z')));
        $this->assertSame('AA', $this->exportData->columnLabel(ord('Z') + 1));
        $this->assertSame('AZ', $this->exportData->columnLabel(ord('Z') + 26));
    }
}
