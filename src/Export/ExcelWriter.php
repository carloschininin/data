<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

use CarlosChininin\Util\File\FileDownload;
use CarlosChininin\Util\File\FileDto;
use Symfony\Component\HttpFoundation\Response;
use Vtiful\Kernel\Excel;
use Vtiful\Kernel\Format;

/**
 * Class experimental export data in excel format.
 */
class ExcelWriter extends Export
{
    private Excel $spreadsheet;

    public function __construct(array $items = [], array $headers = [], array $options = [])
    {
        parent::__construct($items, $headers, $options);

        $this->createExcel();
    }

    public function execute(): static
    {
        $this->spreadsheet = $this->spreadsheet
            ->header($this->headers())
            ->setRow('A1', 18, $this->style())
            ->data($this->items());

        return $this;
    }

    public function setHeader(array $headers): static
    {
        $headers = array_values($headers);
        $this->spreadsheet = $this->spreadsheet
            ->header($headers);

        return $this;
    }

    public function setData(array $data): static
    {
        $this->spreadsheet = $this->spreadsheet
            ->data($data);

        return $this;
    }

    public function setCellValue(int $row, int $colum, mixed $value, ?string $dataFormat = null, string $dataType = DataType::STRING): static
    {
        if (null === $value) {
            return $this;
        }

        if ($value instanceof \DateTimeInterface) {
            $value = $value->getTimestamp();
            $dataType = DataType::DATE;
            $dataFormat = $dataFormat ?? DataFormat::DATE_DDMMYYYY;
        }

        if (\is_bool($value)) {
            $value = $value ? 'SI' : 'NO';
        }

        $this->spreadsheet = match ($dataType) {
            DataType::DATE => $this->spreadsheet->insertDate($row, $colum, $value, $dataFormat),
            DataType::FORMULA => $this->spreadsheet->insertFormula($row, $colum, $value),
            default => $this->spreadsheet->insertText($row, $colum, $value, $dataFormat),
        };

        return $this;
    }

    public function download(string $fileName, array $params = []): Response
    {
        $file = $this->file($fileName, $params);

        return (new FileDownload())->down($file);
    }

    public function file(string $fileName, array $params = []): FileDto
    {
        if (!str_ends_with($fileName, '.xlsx')) {
            $fileName .= '.xlsx';
        }

        return new FileDto($fileName, $this->spreadsheet->output());
    }

    public function setHeaderStyle(): static
    {
        if (isset($this->options['memory']) && true === $this->options['memory']) {
            return $this;
        }

        $this->spreadsheet = $this->spreadsheet
            ->setRow('A1', 18, $this->style());

        return $this;
    }

    protected function generateName(): string
    {
        return uniqid();
    }

    private function createExcel(): void
    {
        if (!\extension_loaded('xlswriter')) {
            throw new ExportException('No load extension xlswriter');
        }

        $options = $this->options();

        if (!isset($options['path'])) {
            $options['path'] = sys_get_temp_dir();
        }

        if (isset($options['memory']) && true === $options['memory']) {
            $this->spreadsheet = (new Excel($options))
                ->constMemory($this->generateName(), 'DATA');
        } else {
            $this->spreadsheet = (new Excel($options))
                ->fileName($this->generateName(), 'DATA');
        }
    }

    protected function style()
    {
        $fileHandle = $this->spreadsheet->getHandle();

        return (new Format($fileHandle))
            ->bold()
            ->align(Format::FORMAT_ALIGN_JUSTIFY, Format::FORMAT_ALIGN_VERTICAL_CENTER)
//            ->border(Format::BORDER_THICK)
//            ->background(Format::COLOR_SILVER)
            ->toResource();
    }
}
