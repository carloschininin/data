<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

class ExportException extends \RuntimeException
{
    public function __construct($message = 'Export')
    {
        parent::__construct(sprintf('Error: %s', $message));
    }
}
