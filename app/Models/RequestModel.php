<?php

namespace App\Models;

use App\Traits\Requests\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HigherOrderCollectionProxy;
use NumberToWords\NumberToWords;

/**
 * @property-read User|null $user
 * @property HigherOrderCollectionProxy|mixed $user_id
 * @method static Builder|RequestModel newModelQuery()
 * @method static Builder|RequestModel newQuery()
 * @method static Builder|RequestModel query()
 * @property int $id
 * @property string|null $concept
 * @property string|null $cost_center
 * @property string|null $payee
 * @property string|null $amount
 * @property int|null $type
 * @property string|null $bank
 * @property string|null $card
 * @property string|null $account
 * @property string|null $branch
 * @property string|null $reference
 * @property string|null $covenant
 * @property int|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|RequestModel whereAccount($value)
 * @method static Builder|RequestModel whereAmount($value)
 * @method static Builder|RequestModel whereBank($value)
 * @method static Builder|RequestModel whereBranch($value)
 * @method static Builder|RequestModel whereCard($value)
 * @method static Builder|RequestModel whereConcept($value)
 * @method static Builder|RequestModel whereCostCenter($value)
 * @method static Builder|RequestModel whereCovenant($value)
 * @method static Builder|RequestModel whereCreatedAt($value)
 * @method static Builder|RequestModel whereId($value)
 * @method static Builder|RequestModel wherePayee($value)
 * @method static Builder|RequestModel whereReference($value)
 * @method static Builder|RequestModel whereStatus($value)
 * @method static Builder|RequestModel whereType($value)
 * @method static Builder|RequestModel whereUpdatedAt($value)
 * @method static Builder|RequestModel whereUserId($value)
 * @property-read Collection<int, Message> $messages
 * @property-read int|null $messages_count
 * @property int $is_transfer
 * @method static Builder|RequestModel whereIsTransfer($value)
 * @property-read \App\Models\CostCenter|null $costCenter
 * @property-read Collection<int, \App\Models\FileManagement> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Type|null $typeModel
 * @property int $edit_count
 * @property-read \App\Models\CostCenter|null $costCenterModel
 * @property-read Collection<int, \App\Models\RequestRecords> $records
 * @property-read int|null $records_count
 * @method static Builder<static>|RequestModel whereEditCount($value)
 * @mixin \Eloquent
 */
class RequestModel extends Model
{
    use HasFactory;
    use Status;

    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'concept',
        'cost_center',
        'payee',
        'amount',
        'type',
        'bank',
        'card',
        'account',
        'branch',
        'reference',
        'covenant',
        'status',
        'is_transfer',
        'edit_count'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'request_id');
    }

    public function costCenterModel()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center', 'name');
    }

    public function typeModel()
    {
        return $this->belongsTo(Type::class, 'type', 'key');
    }

    public function getTypeName() {
        return $this->typeModel?->name ?? 'Desconocido';
    }

    public function getFormattedAmount() {
        return '$' . number_format($this->amount, 2);
    }

    public function getPaymentMethod() {
        return $this->is_transfer ? 'Transferencia' : 'Efectivo';
    }

    public function files() {
        return $this->hasMany(FileManagement::class, 'request_id');
    }

    public function records() {
        return $this->hasMany(RequestRecords::class, 'request_id');
    }

    public function addEditCount() {
        $this->edit_count = $this->edit_count + 1;
    }

    public function changeStatusWithRecord(string $status, User|null $user = null) {
        $oldStatus = $this->getStatusText();
        $this->status = $status;
        $newStatus = $this->getStatusText();
        $this->save();

        RequestRecords::changeStatus($user ?? Auth::user(), $this->id, $oldStatus, $newStatus);
    }

    public function updateWithRecord(array $validated, User|null $user = null) {
        $oldData = $this->toArray();
        $this->addEditCount();
        $this->update($validated);

        RequestRecords::edited($user ?? Auth::user(), $this, $oldData);
    }

    public function formattedAmount() {
        return '$' . number_format($this->amount, 2);
    }

    public function amountToWord() {
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('es');

        [$integer, $decimal] = explode('.', (string) $this->amount);

        $intText = $numberTransformer->toWords($integer);
        $decText = $numberTransformer->toWords($decimal);

        $text = "$intText pesos con $decText centavos";

        return ucfirst($text);
    }
}
