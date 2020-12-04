<?php

declare(strict_types=1);


namespace CarlosChininin\Data\Export;


abstract class Export
{
    protected $items;
    protected $headers;
    protected $options;

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
}