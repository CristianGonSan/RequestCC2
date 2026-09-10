<?php

namespace App\Models\Incomes;

use App\Models\Catalogs\CostCenter;
use App\Models\Catalogs\Type;
use App\Models\User;
use App\Traits\Models\CurrencyToWords;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cost_center_id
 * @property int $type_id
 * @property string $concept
 * @property string $payee
 * @property numeric $amount
 * @property bool $is_transfer
 * @property string|null $bank
 * @property string|null $card
 * @property string|null $account
 * @property string|null $branch
 * @property string|null $reference
 * @property string|null $covenant
 * @property Carbon $income_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CostCenter $costCenter
 * @property-read string $amount_formatted
 * @property-read string $amount_to_word
 * @property-read string $payment_method
 * @property-read Type $type
 * @property-read User $user
 * @method static Builder<static>|Income newModelQuery()
 * @method static Builder<static>|Income newQuery()
 * @method static Builder<static>|Income query()
 * @method static Builder<static>|Income whereAccount($value)
 * @method static Builder<static>|Income whereAmount($value)
 * @method static Builder<static>|Income whereBank($value)
 * @method static Builder<static>|Income whereBranch($value)
 * @method static Builder<static>|Income whereCard($value)
 * @method static Builder<static>|Income whereConcept($value)
 * @method static Builder<static>|Income whereCostCenterId($value)
 * @method static Builder<static>|Income whereCovenant($value)
 * @method static Builder<static>|Income whereCreatedAt($value)
 * @method static Builder<static>|Income whereId($value)
 * @method static Builder<static>|Income whereIncomeDate($value)
 * @method static Builder<static>|Income whereIsTransfer($value)
 * @method static Builder<static>|Income wherePayee($value)
 * @method static Builder<static>|Income whereReference($value)
 * @method static Builder<static>|Income whereTypeId($value)
 * @method static Builder<static>|Income whereUpdatedAt($value)
 * @method static Builder<static>|Income whereUserId($value)
 * @mixin \Eloquent
 */
class Income extends Model
{
    use CurrencyToWords, HasFactory, TruncateText;

    protected $fillable = [
        'user_id',
        'cost_center_id',
        'type_id',
        'concept',
        'payee',
        'amount',
        'bank',
        'card',
        'account',
        'branch',
        'reference',
        'covenant',
        'is_transfer',
        'income_date',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'income_date' => 'date',
        'is_transfer' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function getPaymentMethodAttribute(): string
    {
        return $this->is_transfer ? 'Transferencia' : 'Efectivo';
    }

    public function getAmountFormattedAttribute(): string
    {
        return '$'.number_format($this->amount, 2);
    }

    public function getAmountToWordAttribute(): string
    {
        return $this->toCurrencyWords('amount');
    }

    public function isCurrentUser(): bool
    {
        return $this->user_id === auth()->id();
    }
}
