<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ExportExcel extends ExportData
{
    public function __construct(array $items = [], array $headers = [], array $options = [], bool $removeWorksheet = false)
    {
        $options = array_merge(['type' => self::EXCEL], $options);
        parent::__construct($items, $headers, $options, $removeWorksheet);
    }

    public function headerStyle(array $style = [], string $range = null): static
    {
        $range = $range ?? $this->range();

        $defaultStyle = [
            'font' => [
                'bold' => true,
                'size' => '11',
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true, // auto adjusted
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => [
                        'argb' => 'A0000000',
                    ],
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '215967', // 'A9D08E',
                ],
            ],
        ];

        $style = array_merge($defaultStyle, $style);

        $this->style($style, $range);

        return $this;
    }

    public function columnAutoSize(string $start = null, string $end = null): static
    {
        $columnStart = $start ? \ord($start) : \ord($this->col);
        $columnEnd = $end ? \ord($end) : (\count($this->headers) + $columnStart + 1);

        for ($i = $columnStart; $i <= $columnEnd; ++$i) {
            $column = $this->columnLabel($i);
            $this->sheet()->getColumnDimension($column)->setAutoSize(true);
        }

        return $this;
    }

    public function pageSetup(array $options = []): static
    {
        $setup = $this->sheet()->getPageSetup();

        if (isset($options['scale'])) { // 10 - 400
            try {
                $setup->setScale($options['scale']);
            } catch (Exception $e) {
                throw new ExportException('Escala de pagina');
            }
        }
        if (isset($options['fitToPage'])) { // true false
            $setup->setFitToPage((bool) $options['fitToPage']);
        }
        if (isset($options['fitToWidth'])) { // 1 0
            $setup->setFitToWidth((int) $options['fitToWidth']);
        }
        if (isset($options['fitToHeight'])) { // 1 0
            $setup->setFitToHeight((int) $options['fitToHeight']);
        }
        if (isset($options['orientation'])) {
            $setup->setOrientation($options['orientation']); // default landscape portrait
        }
        if (isset($options['paperSize'])) {
            $paperSize = match (mb_strtoupper($options['paperSize'])) {
                'A3' => PageSetup::PAPERSIZE_A3,
                'A2' => PageSetup::PAPERSIZE_A2_PAPER,
                'A5' => PageSetup::PAPERSIZE_A5,
                default => PageSetup::PAPERSIZE_A4,
            };

            $setup->setPaperSize($paperSize); // default landscape portrait
        }

        return $this;
    }

    public function borderStyle(array $style = [], string $range = null): static
    {
        $range = $range ?? $this->range();

        $defaultStyle = [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => [
                    'argb' => 'A0000000',
                ],
            ],
        ];

        if (empty($style)) {
            $style = $defaultStyle;
        }

        $this->sheet()->getStyle($range)->getBorders()->applyFromArray($style);

        return $this;
    }

    public function fontStyle(array $style = [], string $range = null): static
    {
        $range = $range ?? $this->range();

        $defaultStyle = [
            'size' => '11',
            'color' => [
                'rgb' => '000000',
            ],
        ];

        if (empty($style)) {
            $style = $defaultStyle;
        }

        $this->sheet()->getStyle($range)->getFont()->applyFromArray($style);

        return $this;
    }

    public function style(array $style, string $range): static
    {
        try {
            $this->sheet()->getStyle($range)->applyFromArray($style);
        } catch (Exception $exception) {
            throw new ExportException('Failed style '.$exception->getMessage());
        }

        return $this;
    }

    public function setCellValueAndMerge(string $range, $value, array $style = []): static
    {
        parent::setCellValueAndMerge($range, $value);

        if (!empty($style)) {
            $this->style($style, $range);
        }

        return $this;
    }

    public static function toExcelDate(?\DateTimeInterface $dateTime): ?float
    {
        if (null === $dateTime) {
            return null;
        }

        return 25569 + $dateTime->getTimestamp() / 86400;
    }
}
