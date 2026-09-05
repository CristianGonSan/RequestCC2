<?php

namespace App\Models\Catalogs;

use App\Models\MoneyRequests\MoneyRequest;
use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


/**
 * @property int $id
 * @property int|null $company_id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Catalogs\Company|null $company
 * @property-read int|null $moneyRequests_count
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
 * @property-read Collection<int, MoneyRequest> $moneyRequests
 * @property-read int|null $money_requests_count
 * @mixin \Eloquent
 */
class CostCenter extends Model
{
    use HasActiveState, HasFactory, TruncateText;

    protected $table = 'cost_centers';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isInUse(): bool
    {
        return $this->moneyRequests()->exists();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function moneyRequests(): HasMany
    {
        return $this->hasMany(MoneyRequest::class, 'cost_center_id', 'id');
    }
}
