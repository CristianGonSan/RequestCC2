<?php

namespace App\Models\MoneyRequests;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $money_request_id
 * @property int|null $user_id
 * @property string|null $action
 * @property string|null $details
 * @property Carbon $registered_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\MoneyRequests\MoneyRequest|null $request
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereMoneyRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MoneyRequestRecords whereUserId($value)
 * @mixin \Eloquent
 */
class MoneyRequestRecords extends Model
{
    use HasFactory;

    public const CHANGE_STATUS = 'change_status';

    public const EDITED = 'edited';

    public const ACTION_TEXT = [
        self::CHANGE_STATUS => 'Cambio de Estatus',
        self::EDITED        => 'Editada',
    ];

    public const ACTION_BS_CLASS = [
        self::CHANGE_STATUS => 'success',
        self::EDITED        => 'warning',
    ];

    protected $fillable = [
        'money_request_id',
        'user_id',
        'action',
        'details',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(MoneyRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionText(): string
    {
        return self::ACTION_TEXT[$this->action] ?? 'Desconocido';
    }

    public function getActionBSClass(): string
    {
        return self::ACTION_BS_CLASS[$this->action] ?? 'secondary';
    }
}
