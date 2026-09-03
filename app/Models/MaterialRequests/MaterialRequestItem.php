<?php

namespace App\Models\MaterialRequests;

use App\Models\Catalogs\Material;
use App\Models\Catalogs\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $material_request_id
 * @property int $material_id
 * @property int $unit_id
 * @property numeric $quantity_requested
 * @property numeric $quantity_fulfilled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, MaterialRequestFulfillment> $fulfillments
 * @property-read int|null $fulfillments_count
 * @property-read Material $material
 * @property-read MaterialRequest $materialRequest
 * @property-read Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereMaterialRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereQuantityFulfilled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereQuantityRequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestItem whereUpdatedAt($value)
 * @property-read float $remaining_quantity
 * @property-read float $total_fulfilled_cost
 * @property-read float $total_fulfilled_quantity
 * @property-read float $percentage_fulfilled
 * @property-read float $total_spent
 * @mixin \Eloquent
 */
class MaterialRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_request_id',
        'material_id',
        'unit_id',
        'quantity_requested',
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:3',
        'quantity_fulfilled' => 'decimal:3',
    ];

    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function fulfillments(): HasMany
    {
        return $this->hasMany(MaterialRequestFulfillment::class);
    }

    public function isCurrentUser(): bool
    {
        return $this->materialRequest->isCurrentUser();
    }

    public function getRemainingQuantityAttribute(): float
    {
        return $this->quantity_requested - $this->quantity_fulfilled;
    }

    public function getPercentageFulfilledAttribute(): float
    {
        if ($this->quantity_requested === 0) {
            return 0;
        }

        return ($this->quantity_fulfilled / $this->quantity_requested) * 100;
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->fulfillments()->sum('cost');
    }

    public function isFullyFulfilled(): bool
    {
        return $this->quantity_fulfilled >= $this->quantity_requested;
    }
}
