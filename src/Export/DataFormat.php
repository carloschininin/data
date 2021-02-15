<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

interface DataFormat
{
    const GENERAL = 'General';

    const TEXT = '@';

    const NUMBER = '0';
    const NUMBER_00 = '0.00';
    const NUMBER_COMMA_SEPARATED1 = '#,##0.00';
    const NUMBER_COMMA_SEPARATED2 = '#,##0.00_-';

    const PERCENTAGE = '0%';
    const PERCENTAGE_00 = '0.00%';

    const DATE_YYYYMMDD2 = 'yyyy-mm-dd';
    const DATE_YYYYMMDD = 'yyyy-mm-dd';
    const DATE_DDMMYYYY = 'dd/mm/yyyy';
    const DATE_DMYSLASH = 'd/m/yy';
    const DATE_DMYMINUS = 'd-m-yy';
    const DATE_DMMINUS = 'd-m';
    const DATE_MYMINUS = 'm-yy';
    const DATE_XLSX14 = 'mm-dd-yy';
    const DATE_XLSX15 = 'd-mmm-yy';
    const DATE_XLSX16 = 'd-mmm';
    const DATE_XLSX17 = 'mmm-yy';
    const DATE_XLSX22 = 'm/d/yy h:mm';
    const DATE_DATETIME = 'd/m/yy h:mm';
    const DATE_TIME1 = 'h:mm AM/PM';
    const DATE_TIME2 = 'h:mm:ss AM/PM';
    const DATE_TIME3 = 'h:mm';
    const DATE_TIME4 = 'h:mm:ss';
    const DATE_TIME5 = 'mm:ss';
    const DATE_TIME6 = 'h:mm:ss';
    const DATE_TIME7 = 'i:s.S';
    const DATE_TIME8 = 'h:mm:ss;@';
    const DATE_YYYYMMDDSLASH = 'yyyy/mm/dd;@';

    const CURRENCY_SOL_SIMPLE = '"S/"#,##0.00_-';
    const CURRENCY_SOL = 'S/#,##0_-';
    const CURRENCY_USD_SIMPLE = '"$"#,##0.00_-';
    const CURRENCY_USD = '$#,##0_-';
    const CURRENCY_EUR_SIMPLE = '#,##0.00_-"€"';
    const CURRENCY_EUR = '#,##0_-"€"';
    const ACCOUNTING_USD = '_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)';
}
