<?php

namespace App\Models\MaterialRequests;

use App\Enums\Requests\MaterialRequestStatus;
use App\Models\Catalogs\CostCenter;
use App\Models\Catalogs\Type;
use App\Models\User;
use App\Traits\Models\TruncateText;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use NumberToWords\NumberToWords;
use App\Traits\Models\CurrencyToWords;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cost_center_id
 * @property int $type_id
 * @property string|null $concept
 * @property MaterialRequestStatus $status
 * @property numeric $total_spent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, MaterialRequestItem> $items
 * @property-read int|null $items_count
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereConcept($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereCostCenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereTotalSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialRequest whereUserId($value)
 *
 * @property-read CostCenter $costCenter
 * @property-read Collection<int, MaterialRequestFulfillment> $fulfillments
 * @property-read int|null $fulfillments_count
 * @property-read Type $type
 *
 * @mixin \Eloquent
 */
class MaterialRequest extends Model
{
    use CurrencyToWords, TruncateText , HasFactory;

    protected $fillable = [
        'user_id',
        'cost_center_id',
        'type_id',
        'concept',
        'status',
    ];

    protected $casts = [
        'status'      => MaterialRequestStatus::class,
        'total_spent' => 'decimal:2',
    ];

    public function canDelete(): bool
    {
        return $this->status->isPending() && $this->user_id === Auth::id();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    public function fulfillments(): HasMany
    {
        return $this->hasMany(MaterialRequestFulfillment::class);
    }

    public function isCurrentUser(): bool
    {
        return $this->user_id === auth()->id();
    }

    public function isFulfilled(): bool
    {
        return $this->items()->whereColumn('quantity_requested', '>', 'quantity_fulfilled')->doesntExist();
    }

    public function totalSpentToWords(): string
    {
        return $this->toCurrencyWords('total_spent');
    }
}
