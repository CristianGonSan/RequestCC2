<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DataBag
{
    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public static function make(array $items = []): static
    {
        return new static($items);
    }

    public function dataBag(string $key, array $default = []): self
    {
        return DataBag::make($this->array($key, $default));
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->items, $key, $default);
    }

    public function set(string $key, mixed $value): static
    {
        Arr::set($this->items, $key, $value);

        return $this;
    }

    public function has(string $key): bool
    {
        return Arr::has($this->items, $key);
    }

    public function filled(string $key): bool
    {
        return filled($this->get($key));
    }

    public function forget(string $key): static
    {
        Arr::forget($this->items, $key);

        return $this;
    }

    // ---- Accesores tipados ----

    public function int(string $key, int $default = 0): int
    {
        return (int) $this->get($key, $default);
    }

    public function float(string $key, float $default = 0.0): float
    {
        return (float) $this->get($key, $default);
    }

    public function string(string $key, string $default = ''): string
    {
        return (string) $this->get($key, $default);
    }

    public function boolean(string $key, bool $default = false): bool
    {
        $value = $this->get($key, $default);

        if (\is_string($value)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return (bool) $value;
    }

    public function array(string $key, array $default = []): array
    {
        $value = $this->get($key, $default);

        return \is_array($value) ? $value : $default;
    }

    public function collect(?string $key = null): Collection
    {
        return collect($key === null ? $this->items : $this->array($key));
    }

    public function all(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->items, $options);
    }
}
