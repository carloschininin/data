<?php

declare(strict_types=1);


namespace CarlosChininin\Data\Export;


use CarlosChininin\Util\File\FileDto;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class ExportExcel extends Export
{
    private $spreadsheet;
    private $col;
    private $row;

    public function __construct(array $items, array $headers = [], int $row = 1, string $col = 'A')
    {
        parent::__construct($items, $headers);
        $this->spreadsheet = new Spreadsheet();
        $this->col = $col;
        $this->row = $row;
    }

    private function sheet(): Worksheet
    {
        return $this->spreadsheet->getActiveSheet();
    }

    public function execute(): FileDto
    {
        //establecer las cabeceras
        $column = \ord($this->col) - 1;
        foreach ($this->headers as $key => $label) {
            ++$column;
            $this->sheet()->setCellValue(\chr($column).$this->row, $label);
        }

        $i = $this->row + 1;
        foreach ($this->items as $item) {
            $column = \ord($this->col) - 1;
            foreach ($this->headers as $key => $label) {
                ++$column;
                $this->sheet()->setCellValue(\chr($column).$i,$this->itemByKey($item, $key) );
            }
            ++$i;
        }

//        $this->headerSheet($this->start, \chr($column), $this->row);
//        $this->dataStyle($this->start, \chr($column), $this->row);

        return $this->file();
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

    protected function file(): FileDto
    {
        $writer = new Xlsx($this->spreadsheet);
//        $fileName = Generator::slugify($this->sheetTitle()).'_'.(new DateTime())->format('dmY_his').'.xlsx';
        $fileName = 'data.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        try {
            $writer->save($tempFile);
        } catch (Exception $e) {
        }

        return new FileDto($fileName, $tempFile);
    }
}