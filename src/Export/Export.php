<?php

declare(strict_types=1);


namespace CarlosChininin\Data\Export;


use CarlosChininin\Util\File\FileDto;

abstract class Export
{
    protected $items;
    protected $headers;

    public function __construct(array $items, array $headers = [])
    {
        $this->items = $items;
        $this->headers = $headers;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    abstract public function execute(): FileDto;
}