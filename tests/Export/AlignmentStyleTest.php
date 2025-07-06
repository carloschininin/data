<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Tests\Export;

use CarlosChininin\Data\Export\AlignmentStyle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PHPUnit\Framework\TestCase;

class AlignmentStyleTest extends TestCase
{
    public function testAlignmentStyleConstants(): void
    {
        $this->assertSame(Alignment::HORIZONTAL_LEFT, AlignmentStyle::HORIZONTAL_LEFT);
        $this->assertSame(Alignment::HORIZONTAL_RIGHT, AlignmentStyle::HORIZONTAL_RIGHT);
        $this->assertSame(Alignment::HORIZONTAL_CENTER, AlignmentStyle::HORIZONTAL_CENTER);
        $this->assertSame(Alignment::VERTICAL_BOTTOM, AlignmentStyle::VERTICAL_BOTTOM);
        $this->assertSame(Alignment::VERTICAL_TOP, AlignmentStyle::VERTICAL_TOP);
        $this->assertSame(Alignment::VERTICAL_CENTER, AlignmentStyle::VERTICAL_CENTER);
    }
}
