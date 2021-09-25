<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

use CarlosChininin\Util\File\FileDownload;
use CarlosChininin\Util\File\FileDto;
use DateTime;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;

class ExportData extends Export
{
    public const EXCEL = 'XLSX';
    public const EXCEL_OLD = 'XLS';
    public const CSV = 'CSV';

    public const PDF_DOMPDF = 'DOMPDF';
    public const PDF_MPDF = 'MPDF';
    public const PDF_TCPDF = 'TCPDF';

    private Spreadsheet $spreadsheet;
    protected string $col;
    protected int $row;
    private string $type;

    public function __construct(array $items = [], array $headers = [], array $options = [], bool $removeWorksheet = false)
    {
        parent::__construct($items, $headers, $options);
        $this->spreadsheet = new Spreadsheet();
        if ($removeWorksheet) {
            $this->removeSheet();
        }
        $this->valuesOfOptions();
    }

    public function removeSheet(int $index = 0): bool
    {
        try {
            $this->spreadsheet->removeSheetByIndex($index);
            return true;
        } catch (Exception $e) {
        }

        return false;
    }

    public function addSheet(string $title): ?Worksheet
    {
        try {
            $this->spreadsheet->addSheet(new Worksheet($this->spreadsheet, $title));
            return $this->spreadsheet->setActiveSheetIndexByName($title);
        } catch (Exception $e) {
        }

        return null;
    }

    public function setActiveSheet(int $index): ?Worksheet
    {
        try {
            return $this->spreadsheet->setActiveSheetIndex($index);
        } catch (Exception $e) {
        }

        return null;
    }

    public function sheet(?int $index = null): Worksheet
    {
        if (null === $index || $index < 0) {
            return $this->spreadsheet->getActiveSheet();
        }

        try {
            return $this->spreadsheet->getSheet($index);
        } catch (Exception $e) {
        }

        return $this->spreadsheet->getActiveSheet();
    }

    public function execute(): self
    {
        $this->applyHeaders();
        $this->applyItems();

        return $this;
    }

    public function applyHeaders(): self
    {
        // Establecer las cabeceras
        $column = \ord($this->col) - 1;
        foreach ($this->headers as $key => $label) {
            ++$column;
            $this->setCellValue(\chr($column).$this->row, $this->labelHeader($label));
        }

        return $this;
    }

    public function applyItems(): self
    {
        // Colocar los datos
        $i = $this->row + 1;
        foreach ($this->items as $item) {
            $column = \ord($this->col) - 1;
            foreach ($this->headers as $key => $label) {
                ++$column;
                $this->setCellValue(
                    \chr($column).$i,
                    $this->itemByKey($item, $key),
                    $this->typeHeader($label),
                    $this->formatHeader($label)
                );
            }
            ++$i;
        }

        return $this;
    }

    /** @param string|array $value */
    protected function labelHeader($value): string
    {
        return $value['label'] ?? $value;
    }

    /** @param string|array $value */
    protected function typeHeader($value): ?string
    {
        return $value['type'] ?? null;
    }

    /** @param string|array $value */
    protected function formatHeader($value): ?string
    {
        return $value['format'] ?? null;
    }

    public function setCellValue(string $position, $value, string $dataType = null, ?string $dataFormat = null): self
    {
        if (DataType::DATE === $dataType) {
            $value = Date::PHPToExcel($value);
            $dataFormat = $dataFormat ?? DataFormat::DATE_DDMMYYYY;
        }

        if (null === $dataType || DataType::DATE === $dataType) {
            $this->sheet()->setCellValue($position, $value);
        } else {
            $this->sheet()->setCellValueExplicit($position, $value, $dataType);
        }

        if (null !== $dataFormat) {
            $this->sheet()->getStyle($position)->getNumberFormat()->setFormatCode($dataFormat);
        }

        return $this;
    }

    public function setCellValueAndMerge(string $range, $value): self
    {
        list($ini, $fin) = explode(':', $range);

        return $this->mergeCell($range)->setCellValue($ini, $value);
    }

    public function setCellFromArray(string $startCell, array $items, $nullValue = null): self
    {
        $this->sheet()->fromArray($items, $nullValue, $startCell);

        return $this;
    }

    public function mergeCell(string $range): self
    {
        try {
            $this->sheet()->mergeCells($range);
        } catch (Exception $e) {
            throw new ExportException('Fallo union de celdas');
        }

        return $this;
    }

    public function range(): string
    {
        $end = \count($this->headers) + \ord($this->col) - 1;

        return $this->col.$this->row.':'.\chr($end).$this->row;
    }

    private function itemByKey(array $item, string $key)
    {
        $indexes = explode('.', $key);

        return $this->item($item, $indexes, 0);
    }

    private function item($item, array $indexes, int $count)
    {
        $key = $indexes[$count];
        if (!isset($item[$key])) {
            return null;
        }

        if (\is_array($item[$key])) {
            return $this->item($item[$key], $indexes, $count + 1);
        }

        return $item[$key];
    }

    public function styleColDate(string $range): void
    {
        $this->sheet()->getStyle($range)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
    }

    public function styleColTime(string $range): void
    {
        $this->sheet()->getStyle($range)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME3);
    }

    public function file(string $fileName, array $params = []): FileDto
    {
        $writer = $this->fileWriter();
        $fileName = $this->encodeName($fileName, $params);
        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return new FileDto($fileName, $filePath);
    }

    public function download(string $fileName, array $params = []): Response
    {
        $file = $this->file($fileName, $params);

        return (new FileDownload())->down($file);
    }

    protected function encodeName(string $fileName, array $params): string
    {
        if (empty($params)) {
            return $fileName.$this->fileExtension();
        }

        if (isset($params['date']) && true === $params['date']) {
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
        $this->col = $options['col'] ?? 'A';
        $this->row = $options['row'] ?? 1;
        $this->type = $options['type'] ?? self::EXCEL;
    }

    protected function fileExtension(): string
    {
        if (\in_array($this->type, [self::PDF_DOMPDF, self::PDF_MPDF, self::PDF_TCPDF], true)) {
            return '.pdf';
        }

        return '.'.mb_strtolower($this->type);
    }

    protected function fileWriter(string $type = null): BaseWriter
    {
        $type = $type ?? $this->type;
        if (self::EXCEL === $type) {
            return new Xlsx($this->spreadsheet);
        }

        if (self::EXCEL_OLD === $type) {
            return new Xls($this->spreadsheet);
        }

        if (self::CSV === $type) {
            return new Csv($this->spreadsheet);
        }

        if (self::PDF_DOMPDF === $type) {
            return new Dompdf($this->spreadsheet);
        }

        if (self::PDF_MPDF === $type) {
            return new Mpdf($this->spreadsheet);
        }

        if (self::PDF_TCPDF === $type) {
            return new Tcpdf($this->spreadsheet);
        }

        return new Xlsx($this->spreadsheet);
    }
}
