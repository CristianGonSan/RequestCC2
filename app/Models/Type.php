<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $key
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestModel> $requests
 * @property-read int|null $requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'key',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'type_user');
    }

    public function requests()
    {
        return $this->hasMany(RequestModel::class, 'type', 'key');
    }

    public static function options($justEnabled = false)
    {
        $typeOptions = [];

        if ($justEnabled) {
            foreach (Type::select(['key', 'name', 'enabled'])->where('enabled', true)->get() as $type) {
                $typeOptions[$type->key] = $type->name;
            }
        } else {
            foreach (Type::select(['key', 'name'])->get() as $type) {
                $typeOptions[$type->key] = $type->name;
            }
        }

        return $typeOptions;
    }

    public static function getName($key): string
    {
        return Type::where('key', $key)->first()?->name ?? 'Desconocido';
    }

    public static function getEnabledTypes() {
        return Type::where('enabled', true)->get();
    }
}
