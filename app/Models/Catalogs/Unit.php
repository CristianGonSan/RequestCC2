<?php

namespace App\Models\Catalogs;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereUpdatedAt($value)
 *
 * @property-read Collection<int, Material> $materials
 * @property-read int|null $materials_count
 *
 * @mixin \Eloquent
 */
class Unit extends Model
{
    use HasActiveState, TruncateText;

    protected $table = 'units';

    protected $fillable = [
        'name',
        'symbol',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isInUse(): bool
    {
        return $this->materials()->exists();
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'base_unit_id');
    }

    public static function options($onlyActive = true): array
    {
        $query = Unit::query();

        if ($onlyActive) {
            $query->active();
        }

        $options = [];

        foreach ($query->get() as $unit) {
            $options[$unit->id] = "{$unit->name} ({$unit->symbol})";
        }

        return $options;
    }
}
