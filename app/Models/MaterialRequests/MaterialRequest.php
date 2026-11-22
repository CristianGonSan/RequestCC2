<?php

namespace App\Models\MaterialRequests;

use App\Contracts\Common\HasMessages;
use App\Enums\Requests\ChargeType;
use App\Enums\Requests\MaterialRequestStatus;
use App\Models\Catalogs\CostCenter;
use App\Models\Catalogs\Customer;
use App\Models\Catalogs\Type;
use App\Models\Common\Message;
use App\Models\User;
use App\Traits\Models\HasChangeLogs;
use App\Traits\Models\TruncateText;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use App\Traits\Models\CurrencyToWords;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cost_center_id
 * @property int $customer_id
 * @property int $type_id
 * @property string|null $notes
 * @property MaterialRequestStatus $status
 * @property numeric $total
 * @property ChargeType $charge_type
 * @property numeric $charge_rate
 * @property numeric $subtotal
 * @property numeric $charge_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Common\ChangeLog> $changeLogs
 * @property-read int|null $change_logs_count
 * @property-read CostCenter $costCenter
 * @property-read Customer $customer
 * @property-read Collection<int, User> $favoriters
 * @property-read int|null $favoriters_count
 * @property-read Collection<int, \Overtrue\LaravelFavorite\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read Collection<int, \App\Models\MaterialRequests\MaterialRequestFulfillment> $fulfillments
 * @property-read int|null $fulfillments_count
 * @property-read int $edited_change_logs_count
 * @property-read bool $is_fulfilled
 * @property-read string $status_bs_color
 * @property-read string $status_label
 * @property-read string $total_formatted
 * @property-read string|null $total_spent
 * @property-read string $total_spent_formatted
 * @property-read string $total_spent_to_words
 * @property-read string $total_to_words
 * @property-read Collection<int, \App\Models\MaterialRequests\MaterialRequestItem> $items
 * @property-read int|null $items_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\CustomMedia> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Message> $messages
 * @property-read int|null $messages_count
 * @property-read Type $type
 * @property-read User $user
 * @method static Builder<static>|MaterialRequest newModelQuery()
 * @method static Builder<static>|MaterialRequest newQuery()
 * @method static Builder<static>|MaterialRequest query()
 * @method static Builder<static>|MaterialRequest whereChargeAmount($value)
 * @method static Builder<static>|MaterialRequest whereChargeRate($value)
 * @method static Builder<static>|MaterialRequest whereChargeType($value)
 * @method static Builder<static>|MaterialRequest whereCostCenterId($value)
 * @method static Builder<static>|MaterialRequest whereCreatedAt($value)
 * @method static Builder<static>|MaterialRequest whereCustomerId($value)
 * @method static Builder<static>|MaterialRequest whereId($value)
 * @method static Builder<static>|MaterialRequest whereNotes($value)
 * @method static Builder<static>|MaterialRequest whereStatus($value)
 * @method static Builder<static>|MaterialRequest whereSubtotal($value)
 * @method static Builder<static>|MaterialRequest whereTotal($value)
 * @method static Builder<static>|MaterialRequest whereTypeId($value)
 * @method static Builder<static>|MaterialRequest whereUpdatedAt($value)
 * @method static Builder<static>|MaterialRequest whereUserId($value)
 * @mixin \Eloquent
 */
class MaterialRequest extends Model implements HasMedia, HasMessages
{
    use CurrencyToWords, HasChangeLogs, HasFactory , TruncateText, InteractsWithMedia, Favoriteable;

    protected $fillable = [
        'user_id',
        'cost_center_id',
        'customer_id',
        'type_id',
        'notes',
        'status',
        'total',
        'charge_type',
        'charge_rate',
        'subtotal',
        'charge_amount',
    ];

    protected $casts = [
        'status'        => MaterialRequestStatus::class,
        'charge_type'   => ChargeType::class,
        'charge_rate'   => 'decimal:4',
        'subtotal'      => 'decimal:2',
        'charge_amount' => 'decimal:2',
        'total'         => 'decimal:2',
    ];

    public function canDelete(): bool
    {
        return $this->status->isPending() && $this->user_id === Auth::id();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function getTotalFormattedAttribute(): string
    {
        return '$'.number_format_decimal($this->total, 2);
    }

    public function getTotalToWordsAttribute(): string
    {
        return $this->toCurrencyWords('total');
    }

    public function getTotalSpentAttribute(): ?string
    {
        return $this->total;
    }

    public function getTotalSpentFormattedAttribute(): string
    {
        return $this->total_formatted;
    }

    public function getTotalSpentToWordsAttribute(): string
    {
        return $this->total_to_words;
    }

    public function getIsFulfilledAttribute(): bool
    {
        return $this->items()->whereColumn('quantity_requested', '>', 'quantity_fulfilled')->doesntExist();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getStatusBsColorAttribute(): string
    {
        return $this->status->bootstrapColorClass();
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
}
