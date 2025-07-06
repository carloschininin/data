<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Tests\Export;

use CarlosChininin\Data\Export\DataFormat;
use PHPUnit\Framework\TestCase;

class DataFormatTest extends TestCase
{
    public function testDataFormatConstants(): void
    {
        $this->assertSame('General', DataFormat::GENERAL);
        $this->assertSame('@', DataFormat::TEXT);
        $this->assertSame('0', DataFormat::NUMBER);
        $this->assertSame('0.00', DataFormat::NUMBER_00);
        $this->assertSame('#,##0.00', DataFormat::NUMBER_COMMA_SEPARATED1);
        $this->assertSame('#,##0.00_-', DataFormat::NUMBER_COMMA_SEPARATED2);
        $this->assertSame('0%', DataFormat::PERCENTAGE);
        $this->assertSame('0.00%', DataFormat::PERCENTAGE_00);
        $this->assertSame('yyyy-mm-dd', DataFormat::DATE_YYYYMMDD);
        $this->assertSame('dd/mm/yyyy', DataFormat::DATE_DDMMYYYY);
    }
}
