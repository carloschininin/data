<?php

declare(strict_types=1);


namespace CarlosChininin\Data\Export;



final class ExportExcel extends ExportData
{
    public function __construct(array $items, array $headers = [], array $options = [])
    {
        $options = array_merge($options, ['type' => self::EXCEL]);
        parent::__construct($items, $headers, $options);
    }
}