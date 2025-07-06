<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Tests\Export;

use CarlosChininin\Data\Export\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataType as PhpDataType;
use PHPUnit\Framework\TestCase;

class DataTypeTest extends TestCase
{
    public function testDataTypeConstants(): void
    {
        $this->assertSame(PhpDataType::TYPE_STRING, DataType::STRING);
        $this->assertSame(PhpDataType::TYPE_NUMERIC, DataType::NUMBER);
        $this->assertSame(PhpDataType::TYPE_BOOL, DataType::BOOL);
        $this->assertSame(PhpDataType::TYPE_FORMULA, DataType::FORMULA);
        $this->assertSame(PhpDataType::TYPE_INLINE, DataType::INLINE);
        $this->assertSame(PhpDataType::TYPE_NULL, DataType::NULL);
        $this->assertSame('dat', DataType::DATE);
    }
}
