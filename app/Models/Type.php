<?php

namespace App\Models;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $key
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, RequestModel> $requests
 * @property-read int|null $requests_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Type whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Type extends Model
{
    use HasActiveState, HasFactory, TruncateText;

    protected $fillable = [
        'name',
        'description',
        'key',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isInUse(): bool
    {
        return $this->requests()->exists();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'type_user');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(RequestModel::class, 'type_id');
    }

    public static function options(bool $onlyActive = true): array
    {
        if ($onlyActive) {
            return Type::active()->pluck('name', 'id')->toArray();
        }

        return Type::pluck('name', 'id')->toArray();
    }

    public static function optionsByAuth(bool $onlyActive = true): array
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->typeOptions($onlyActive);
    }

    public static function getName(int $id): string
    {
        return Type::where('id', $id)->first()?->name ?? 'Desconocido';
    }
}
