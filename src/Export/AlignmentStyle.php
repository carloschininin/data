<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AlignmentStyle
{
    public const HORIZONTAL_LEFT = Alignment::HORIZONTAL_LEFT;
    public const HORIZONTAL_RIGHT = Alignment::HORIZONTAL_RIGHT;
    public const HORIZONTAL_CENTER = Alignment::HORIZONTAL_CENTER;
    public const VERTICAL_BOTTOM = Alignment::VERTICAL_BOTTOM;
    public const VERTICAL_TOP = Alignment::VERTICAL_TOP;
    public const VERTICAL_CENTER = Alignment::VERTICAL_CENTER;
}
