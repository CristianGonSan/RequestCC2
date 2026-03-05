<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 *
 *
 * @property int $id
 * @property string $key
 * @property array|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Configuration newModelQuery()
 * @method static Builder|Configuration newQuery()
 * @method static Builder|Configuration query()
 * @method static Builder|Configuration whereCreatedAt($value)
 * @method static Builder|Configuration whereId($value)
 * @method static Builder|Configuration whereKey($value)
 * @method static Builder|Configuration whereUpdatedAt($value)
 * @method static Builder|Configuration whereValue($value)
 * @mixin Eloquent
 */
class Configuration extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    public static function getCache(string $key, int $ttl = 60): mixed
    {
        return Cache::remember("configuration_$key", $ttl, function () use ($key) {
            return self::getValue($key);
        });
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return Configuration::where('key', $key)->value('value') ?? $default;
    }

    public static function setValue(string $key, array $value): void
    {
        Configuration::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function deleteValue(string $key): void
    {
        Configuration::where('key', $key)->first()->delete();
    }
}
