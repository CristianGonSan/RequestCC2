<?php

namespace App\Models\Catalogs;

use App\Models\MaterialRequests\MaterialRequestItem;
use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $base_unit_id
 * @property string $name
 * @property string|null $code
 * @property string|null $description
 * @property bool $is_external
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereBaseUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereIsExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUpdatedAt($value)
 * @property-read Unit $baseUnit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MaterialRequestItem> $materialRequestItems
 * @property-read int|null $material_request_items_count
 * @mixin \Eloquent
 */
class Material extends Model
{
    use HasActiveState, TruncateText;

    protected $table = 'materials';

    protected $fillable = [
        'name',
        'code',
        'base_unit_id',
        'description',
        'is_external',
        'is_active',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function isInUse(): bool
    {
        return $this->materialRequestItems()->exists();
    }

    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function materialRequestItems(): HasMany
    {
        return $this->hasMany(MaterialRequestItem::class, 'material_id');
    }
}
