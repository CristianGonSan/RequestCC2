<?php

namespace App\Models;

use App\Support\DataBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $key
 * @property array<array-key, mixed>|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function set(string $key, mixed $value): self
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function has(string $key): bool
    {
        return static::query()->where('key', $key)->exists();
    }

    public static function forget(string $key): bool
    {
        return (bool) static::query()->where('key', $key)->delete();
    }

    public static function allAsArray(): array
    {
        return static::query()->pluck('value', 'key')->toArray();
    }

    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::set($key, $value);
        }
    }

    /*
    |--------------------------------------------------------------------
    | Casting explícito (delegado a DataBag)
    |--------------------------------------------------------------------
    */

    protected static function bag(string $key, mixed $default): DataBag
    {
        return DataBag::make(['value' => static::get($key, $default)]);
    }

    public static function int(string $key, int $default = 0): int
    {
        return static::bag($key, $default)->int('value', $default);
    }

    public static function float(string $key, float $default = 0.0): float
    {
        return static::bag($key, $default)->float('value', $default);
    }

    public static function string(string $key, string $default = ''): string
    {
        return static::bag($key, $default)->string('value', $default);
    }

    public static function boolean(string $key, bool $default = false): bool
    {
        return static::bag($key, $default)->boolean('value', $default);
    }

    public static function array(string $key, array $default = []): array
    {
        $value = static::get($key, $default);

        return \is_array($value) ? $value : ($value === null ? $default : (array) $value);
    }

    public static function collection(string $key, array $default = []): Collection
    {
        return collect(static::array($key, $default));
    }

    public static function dataBag(string $key, array $default = []): DataBag
    {
        return DataBag::make(static::array($key, $default));
    }
}
