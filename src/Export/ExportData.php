<?php

declare(strict_types=1);


namespace CarlosChininin\Data\Export;


use CarlosChininin\Util\File\FileDto;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportData extends Export
{
    public const EXCEL = 'XLSX';
    public const EXCEL_OLD = 'XLS';
    public const CSV = 'CSV';

    private $spreadsheet;
    private $col;
    private $row;
    private $type;

    public function __construct(array $items, array $headers = [], array $options = [])
    {
        parent::__construct($items, $headers, $options);
        $this->spreadsheet = new Spreadsheet();
        $this->valuesOfOptions();
    }

    private function sheet(): Worksheet
    {
        return $this->spreadsheet->getActiveSheet();
    }

    public function execute(): Export
    {
        //establecer las cabeceras
        $column = ord($this->col) - 1;
        foreach ($this->headers as $key => $label) {
            ++$column;
            $this->sheet()->setCellValue(chr($column).$this->row, $label);
        }

        $i = $this->row + 1;
        foreach ($this->items as $item) {
            $column = ord($this->col) - 1;
            foreach ($this->headers as $key => $label) {
                ++$column;
                $this->sheet()->setCellValue(chr($column).$i,$this->itemByKey($item, $key) );
            }
            ++$i;
        }

//        $this->headerSheet($this->start, \chr($column), $this->row);
//        $this->dataStyle($this->start, \chr($column), $this->row);

        return $this;
    }

    private function itemByKey(array $item, string $key)
    {
        $indexes = explode('.', $key);

        return $this->item($item, $indexes, 0);
    }

    private function item($item, array $indexes, int $count)
    {
        $key = $indexes[$count];
        if (is_array($item[$key])) {
            return $this->item($item[$key], $indexes, $count+1);
        }

        return $item[$key];
    }

    public function file(string $fileName, array $params = []): FileDto
    {
        $writer = $this->fileWriter();
        $fileName = $this->encodeName($fileName, $params);
        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return new FileDto($fileName, $filePath);
    }

    protected function encodeName(string $fileName, array $params): string
    {
        if (empty($params)) {
            return $fileName.$this->fileExtension();
        }

        if (isset($params['date']) && true === $params['date'] ) {
            return $fileName.'_'.(new DateTime())->format('dmY').$this->fileExtension();
        }

        if (isset($params['datetime']) && true === $params['datetime']) {
            return $fileName.'_'.(new DateTime())->format('dmY_his').$this->fileExtension();
        }

        return $fileName.$this->fileExtension();
    }

    protected function valuesOfOptions(): void
    {
        $options = $this->options();
        $this->col = isset($options['col']) ? $options['col'] : 'A';
        $this->row = isset($options['row']) ? $options['row'] : 1;
        $this->type = isset($options['type']) ? $options['type'] : self::EXCEL;
    }

    protected function fileExtension(): string
    {
        return '.'.strtolower($this->type);
    }

    protected function fileWriter(): BaseWriter
    {
        if (self::EXCEL === $this->type) {
            return new Xlsx($this->spreadsheet);
        }

        if (self::EXCEL_OLD === $this->type) {
            return new Xls($this->spreadsheet);
        }

        if (self::CSV === $this->type) {
            return new Csv($this->spreadsheet);
        }

        return new Xlsx($this->spreadsheet);
    }
}