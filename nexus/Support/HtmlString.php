<?php

namespace Nexus\Support;

/**
 * 原样输出的 HTML 片段（替代 Nexus\Support\HtmlString）
 */
class HtmlString implements \JsonSerializable
{
    protected string $html;

    public function __construct(string $html = '')
    {
        $this->html = $html;
    }

    public function toHtml(): string
    {
        return $this->html;
    }

    public function isEmpty(): bool
    {
        return $this->html === '';
    }

    public function jsonSerialize(): string
    {
        return $this->html;
    }

    public function __toString(): string
    {
        return $this->html;
    }
}
