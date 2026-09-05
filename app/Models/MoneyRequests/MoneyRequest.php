<?php

namespace App\Models\MoneyRequests;

use App\Enums\Requests\MoneyRequestStatus;
use App\Models\Catalogs\CostCenter;
use App\Models\Catalogs\Type;
use App\Models\User;
use App\Traits\Models\CurrencyToWords;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $cost_center_id
 * @property int|null $type_id
 * @property string|null $concept
 * @property string|null $cost_center_name
 * @property string|null $payee
 * @property numeric|null $amount
 * @property string|null $type_key
 * @property string|null $bank
 * @property string|null $card
 * @property string|null $account
 * @property string|null $branch
 * @property string|null $reference
 * @property string|null $covenant
 * @property MoneyRequestStatus $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $is_transfer
 * @property int $edit_count
 * @property-read CostCenter|null $costCenter
 * @property-read Type|null $type
 * @method static Builder<static>|MoneyRequest newModelQuery()
 * @method static Builder<static>|MoneyRequest newQuery()
 * @method static Builder<static>|MoneyRequest query()
 * @method static Builder<static>|MoneyRequest whereAccount($value)
 * @method static Builder<static>|MoneyRequest whereAmount($value)
 * @method static Builder<static>|MoneyRequest whereBank($value)
 * @method static Builder<static>|MoneyRequest whereBranch($value)
 * @method static Builder<static>|MoneyRequest whereCard($value)
 * @method static Builder<static>|MoneyRequest whereConcept($value)
 * @method static Builder<static>|MoneyRequest whereCostCenterId($value)
 * @method static Builder<static>|MoneyRequest whereCostCenterName($value)
 * @method static Builder<static>|MoneyRequest whereCovenant($value)
 * @method static Builder<static>|MoneyRequest whereCreatedAt($value)
 * @method static Builder<static>|MoneyRequest whereEditCount($value)
 * @method static Builder<static>|MoneyRequest whereId($value)
 * @method static Builder<static>|MoneyRequest whereIsTransfer($value)
 * @method static Builder<static>|MoneyRequest wherePayee($value)
 * @method static Builder<static>|MoneyRequest whereReference($value)
 * @method static Builder<static>|MoneyRequest whereStatus($value)
 * @method static Builder<static>|MoneyRequest whereTypeId($value)
 * @method static Builder<static>|MoneyRequest whereTypeKey($value)
 * @method static Builder<static>|MoneyRequest whereUpdatedAt($value)
 * @method static Builder<static>|MoneyRequest whereUserId($value)
 * @property-read Collection<int, FileManagement> $files
 * @property-read int|null $files_count
 * @property-read Collection<int, MoneyRequestRecords> $records
 * @property-read int|null $records_count
 * @property-read User $user
 * @property-read string $payment_method
 * @property-read Collection<int, \App\Models\MoneyRequests\Message> $messages
 * @property-read int|null $messages_count
 * @mixin \Eloquent
 */
class MoneyRequest extends Model
{
    use HasFactory, TruncateText, CurrencyToWords;

    protected $fillable = [
        'user_id',
        'cost_center_id',
        'type_id',
        'concept',
        'cost_center_name',
        'payee',
        'amount',
        'type_key',
        'bank',
        'card',
        'account',
        'branch',
        'reference',
        'covenant',
        'status',
        'is_transfer',
        'edit_count',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => MoneyRequestStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'money_request_id');
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function paymentMethod(): string
    {
        return $this->is_transfer ? 'Transferencia' : 'Efectivo';
    }

    public function getPaymentMethodAttribute(): string
    {
        return $this->paymentMethod();
    }

    public function files(): HasMany
    {
        return $this->hasMany(FileManagement::class, 'money_request_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(MoneyRequestRecords::class, 'money_request_id');
    }

    public function addEditCount(): int
    {
        return $this->edit_count += 1;
    }

    public function changeStatusWithRecord(MoneyRequestStatus $status, ?User $user = null): void
    {
        $oldStatus = $this->status->label();
        $this->status = $status;
        $newStatus = $this->status->label();
        $this->save();

        MoneyRequestRecords::changeStatus($user ?? Auth::user(), $this->id, $oldStatus, $newStatus);
    }

    public function updateWithRecord(array $validated, ?User $user = null): void
    {
        $oldData = $this->toArray();
        $this->addEditCount();
        $this->update($validated);

        MoneyRequestRecords::edited($user ?? Auth::user(), $this, $oldData);
    }

    public function formattedAmount(): string
    {
        return '$ '.number_format($this->amount, 2);
    }

    public function amountToWord(): string
    {
        return $this->toCurrencyWords('amount');
    }

    public function canDelete(): bool
    {
        return $this->status->isPending() && $this->user_id === Auth::id();
    }

    public function isEditable(): bool
    {
        return $this->status->isPending();
    }

    public function isCurrentUser(): bool
    {
        return $this->user_id === auth()->id();
    }
}
