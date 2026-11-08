<?php

namespace Nexus\Support;

/**
 * 轻量日期工具（替代 nesbot/carbon 的常用方法），基于 DateTimeImmutable。
 */
class CarbonCompat
{
    private \DateTimeImmutable $date;

    public function __construct($value = 'now', ?\DateTimeZone $tz = null)
    {
        $tz = $tz ?? new \DateTimeZone(date_default_timezone_get());
        if ($value instanceof \DateTimeInterface) {
            $this->date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d H:i:s'), $tz) ?: new \DateTimeImmutable('now', $tz);
        } elseif (is_numeric($value)) {
            $this->date = (new \DateTimeImmutable('now', $tz))->setTimestamp((int)$value);
        } else {
            $this->date = new \DateTimeImmutable((string)$value, $tz);
        }
    }

    public static function now(): self
    {
        return new self('now');
    }

    public static function today(): self
    {
        return (new self('now'))->startOfDay();
    }

    public static function tomorrow(): self
    {
        return (new self('+1 day'))->startOfDay();
    }

    public static function yesterday(): self
    {
        return (new self('-1 day'))->startOfDay();
    }

    public static function parse($value): self
    {
        return new self($value);
    }

    public function copy(): self
    {
        return clone $this;
    }

    public function startOfDay(): self
    {
        return new self($this->date->format('Y-m-d 00:00:00'));
    }

    public function endOfDay(): self
    {
        return new self($this->date->format('Y-m-d 23:59:59'));
    }

    public function addDay(int $days = 1): self
    {
        return new self($this->date->modify("+{$days} day"));
    }

    public function subDay(int $days = 1): self
    {
        return new self($this->date->modify("-{$days} day"));
    }

    public function addSeconds(int $seconds): self
    {
        return new self($this->date->modify("+{$seconds} seconds"));
    }

    public function subSeconds(int $seconds): self
    {
        return new self($this->date->modify("-{$seconds} seconds"));
    }

    public function addDays(int $days): self
    {
        return $this->addDay($days);
    }

    public function addMinutes(int $minutes): self
    {
        return new self($this->date->modify("+{$minutes} minutes"));
    }

    public function clone(): self
    {
        return clone $this;
    }

    public function addMonth(int $months = 1): self
    {
        return new self($this->date->modify("+{$months} month"));
    }

    public function subMonth(int $months = 1): self
    {
        return new self($this->date->modify("-{$months} month"));
    }

    public function addHour(int $hours = 1): self
    {
        return new self($this->date->modify("+{$hours} hour"));
    }

    public function startOfMonth(): self
    {
        return new self($this->date->format('Y-m-01 00:00:00'));
    }

    public function endOfMonth(): self
    {
        return new self($this->date->format('Y-m-t 23:59:59'));
    }

    public function isSameDay($other): bool
    {
        $other = $other instanceof self ? $other : new self($other);
        return $this->date->format('Y-m-d') === $other->date->format('Y-m-d');
    }

    public function month(): int
    {
        return (int)$this->date->format('n');
    }

    public function year(): int
    {
        return (int)$this->date->format('Y');
    }

    public function day(): int
    {
        return (int)$this->date->format('j');
    }

    public function diffInDays($other = null, bool $absolute = false): int
    {
        $other = $other ?? self::now();
        $other = $other instanceof self ? $other : new self($other);
        $diff = $this->startOfDay()->date->diff($other->startOfDay()->date)->days ?? 0;
        return (int)$diff;
    }

    public function diffInWeeks($other = null, bool $absolute = false): float
    {
        $other = $other ?? self::now();
        return ($absolute ? abs($this->diffInDays($other)) : $this->diffInDays($other)) / 7;
    }

    public function diffInHours($other = null, bool $absolute = false): int
    {
        $other = $other ?? self::now();
        $other = $other instanceof self ? $other : new self($other);
        $diff = intdiv($this->date->getTimestamp() - $other->date->getTimestamp(), 3600);
        return $absolute ? abs($diff) : $diff;
    }

    public function diffForHumans($other = null, $absolute = false, $short = false): string
    {
        return gettime($this->toDateTimeString(), true, false, true) ?? $this->toDateTimeString();
    }

    public function diffInSeconds($other = null): int
    {
        $other = $other ?? self::now();
        $other = $other instanceof self ? $other : new self($other);
        return $this->date->getTimestamp() - $other->date->getTimestamp();
    }

    public function gte($other): bool
    {
        return $this->date >= ($other instanceof self ? $other->date : (new self($other))->date);
    }

    public function lte($other): bool
    {
        return $this->date <= ($other instanceof self ? $other->date : (new self($other))->date);
    }

    public function gt($other): bool
    {
        return $this->date > ($other instanceof self ? $other->date : (new self($other))->date);
    }

    public function timestamp(): int
    {
        return $this->date->getTimestamp();
    }

    public function toDateTimeString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function toDateString(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function toTimeString(): string
    {
        return $this->date->format('H:i:s');
    }

    public function format(string $format): string
    {
        return $this->date->format($format);
    }

    public function toDateTime(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function __toString(): string
    {
        return $this->toDateTimeString();
    }
}
