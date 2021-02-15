<?php

declare(strict_types=1);


namespace CarlosChininin\Data\Export;

use PhpOffice\PhpSpreadsheet\Cell\DataType as PhpDataType;

interface DataType
{
    public const STRING = PhpDataType::TYPE_STRING;
    public const NUMBER = PhpDataType::TYPE_NUMERIC;
    public const BOOL = PhpDataType::TYPE_BOOL;
    public const FORMULA = PhpDataType::TYPE_FORMULA;
    public const INLINE = PhpDataType::TYPE_INLINE;
    public const NULL = PhpDataType::TYPE_NULL;
}