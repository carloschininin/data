<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\Data\Export;

use CarlosChininin\Util\File\FileDto;

abstract class Export
{
    protected array $items;
    protected array $headers;
    protected array $options;

    public function __construct(array $items, array $headers = [], array $options = [])
    {
        $this->items = $items;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function options(): array
    {
        return $this->options;
    }

    abstract public function execute(): self;

    abstract public function file(string $fileName, array $params = []): FileDto;
}
