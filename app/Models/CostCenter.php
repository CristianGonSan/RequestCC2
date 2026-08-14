<?php

namespace App\Models;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $company_id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestModel> $requests
 * @property-read int|null $requests_count
 * @method static Builder<static>|CostCenter active()
 * @method static Builder<static>|CostCenter inactive()
 * @method static Builder<static>|CostCenter newModelQuery()
 * @method static Builder<static>|CostCenter newQuery()
 * @method static Builder<static>|CostCenter query()
 * @method static Builder<static>|CostCenter whereCompanyId($value)
 * @method static Builder<static>|CostCenter whereCreatedAt($value)
 * @method static Builder<static>|CostCenter whereDescription($value)
 * @method static Builder<static>|CostCenter whereId($value)
 * @method static Builder<static>|CostCenter whereIsActive($value)
 * @method static Builder<static>|CostCenter whereName($value)
 * @method static Builder<static>|CostCenter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostCenter extends Model
{
    use HasFactory, HasActiveState, TruncateText;

    protected $table = 'cost_centers';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function isInUse(): bool
    {
        return $this->requests()->exists();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(RequestModel::class, 'cost_center_id', 'id');
    }
}
