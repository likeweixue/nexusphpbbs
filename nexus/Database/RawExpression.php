<?php

namespace Nexus\Database;

/**
 * 原生 SQL 片段（替代 Illuminate\Database\Query\Expression）
 */
class RawExpression
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
