<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

final class ExportExcel extends ExportData
{
    public function __construct(array $items = [], array $headers = [], array $options = [])
    {
        $options = array_merge(['type' => self::EXCEL], $options);
        parent::__construct($items, $headers, $options);
    }

    public function headerStyle(array $style = [], ?string $range = null): self
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
                    'rgb' => '215967', //'A9D08E',
                ],
            ],
        ];

        $style = array_merge($defaultStyle, $style);

        $this->sheet()
            ->getStyle($range)
            ->applyFromArray($style);

//        $this->sheet()->setAutoFilter($range);

        return $this;
    }

    public function columnAutoSize(string $start = null, string $end = null): self
    {
        $columnStart = $start ? \ord($start) : \ord($this->col);
        $columnEnd = $end ? \ord($end) : (\count($this->headers) + $columnStart + 1);

        for ($i = $columnStart; $i <= $columnEnd; ++$i) {
            $this->sheet()->getColumnDimension(\chr($i))->setAutoSize(true);
        }

        return $this;
    }

    public function pageSetup(array $options = []): self
    {
        $setup = $this->sheet()->getPageSetup();

        if (isset($options['scale'])) { // 10 - 400
            try {
                $setup->setScale($options['scale']);
            } catch (Exception $e) {
                throw new ExportException('Escala de pagina');
            }
        }
        if (isset($options['FitToWidth'])) { //1 0
            $setup->setFitToWidth((int) $options['FitToWidth']);
        }
        if (isset($options['FitToHeight'])) { // 1 0
            $setup->setFitToHeight((int) $options['FitToHeight']);
        }
        if (isset($options['orientation'])) {
            $setup->setOrientation($options['orientation']); //default landscape portrait
        }

        return $this;
    }
}
