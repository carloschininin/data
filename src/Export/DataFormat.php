<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

interface DataFormat
{
    public const GENERAL = 'General';

    public const TEXT = '@';

    public const NUMBER = '0';
    public const NUMBER_00 = '0.00';
    public const NUMBER_COMMA_SEPARATED1 = '#,##0.00';
    public const NUMBER_COMMA_SEPARATED2 = '#,##0.00_-';

    public const PERCENTAGE = '0%';
    public const PERCENTAGE_00 = '0.00%';

    public const DATE_YYYYMMDD = 'yyyy-mm-dd';
    public const DATE_DDMMYYYY = 'dd/mm/yyyy';
    public const DATE_DDMMYYYY2 = 'dd-mm-yyyy';
    public const DATE_DMYSLASH = 'd/m/yy';
    public const DATE_DMYMINUS = 'd-m-yy';
    public const DATE_DMMINUS = 'd-m';
    public const DATE_MYMINUS = 'm-yy';
    public const DATE_XLSX14 = 'mm-dd-yy';
    public const DATE_XLSX15 = 'd-mmm-yy';
    public const DATE_XLSX16 = 'd-mmm';
    public const DATE_XLSX17 = 'mmm-yy';
    public const DATE_XLSX22 = 'm/d/yy h:mm';
    public const DATE_DATETIME = 'd/m/yy h:mm';
    public const DATE_TIME1 = 'h:mm AM/PM';
    public const DATE_TIME2 = 'h:mm:ss AM/PM';
    public const DATE_TIME3 = 'h:mm';
    public const DATE_TIME4 = 'h:mm:ss';
    public const DATE_TIME5 = 'mm:ss';
    public const DATE_TIME6 = 'h:mm:ss';
    public const DATE_TIME7 = 'i:s.S';
    public const DATE_TIME8 = 'h:mm:ss;@';
    public const DATE_YYYYMMDDSLASH = 'yyyy/mm/dd;@';

    public const CURRENCY_SOL_SIMPLE = '"S/"#,##0.00_-';
    public const CURRENCY_SOL = 'S/#,##0_-';
    public const CURRENCY_USD_SIMPLE = '"$"#,##0.00_-';
    public const CURRENCY_USD = '$#,##0_-';
    public const CURRENCY_EUR_SIMPLE = '#,##0.00_-"€"';
    public const CURRENCY_EUR = '#,##0_-"€"';
    public const ACCOUNTING_USD = '_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)';
}
