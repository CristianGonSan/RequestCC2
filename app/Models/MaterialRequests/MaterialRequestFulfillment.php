<?php

namespace App\Models\MaterialRequests;

use App\Models\User;
use App\Observers\MaterialRequests\MaterialRequestFulfillmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $material_request_id
 * @property int $material_request_item_id
 * @property int $user_id
 * @property numeric $quantity
 * @property numeric $cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MaterialRequests\MaterialRequest $materialRequest
 * @property-read \App\Models\MaterialRequests\MaterialRequestItem $materialRequestItem
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereMaterialRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereMaterialRequestItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequestFulfillment whereUserId($value)
 * @mixin \Eloquent
 */
#[ObservedBy(MaterialRequestFulfillmentObserver::class)]
class MaterialRequestFulfillment extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_request_id',
        'material_request_item_id',
        'user_id',
        'quantity',
        'cost',
    ];

    protected $casts = [
        'quantity'  => 'decimal:3',
        'cost'      => 'decimal:2',
    ];

    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function materialRequestItem(): BelongsTo
    {
        return $this->belongsTo(MaterialRequestItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCurrentUser(): bool
    {
        return $this->user_id === auth()->id();
    }
}
