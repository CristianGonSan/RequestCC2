<?php

namespace App\Models\MoneyRequests;

use App\Models\Catalogs\Type;
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

    public static function changeStatus(User $user, int $requestId, string $oldStatus, string $newStatus, $registered_at = null)
    {
        $record = new MoneyRequestRecords;

        $record->money_request_id = $requestId;
        $record->user_id          = $user->id;
        $record->action           = self::CHANGE_STATUS;

        $name  = $user->name;
        $email = $user->email;

        $record->details       = "$name - ($email) Cambió el estatus de <strong>$oldStatus</strong> a <strong>$newStatus</strong>";
        $record->registered_at = $registered_at ?? now();
        $record->save();

        return $record;
    }

    public static function edited(User $user, MoneyRequest $updateMoneyRequest, array $oldData, $registered_at = null)
    {
        $labels = [
            'concept'     => 'Concepto',
            'cost_center' => 'Centro de costos',
            'payee'       => 'Beneficiario',
            'amount'      => 'Monto',
            'type'        => 'Tipo',
            'bank'        => 'Banco',
            'card'        => 'Tarjeta',
            'account'     => 'Cuenta',
            'branch'      => 'Sucursal',
            'reference'   => 'Referencia',
            'covenant'    => 'Convenio',
        ];

        $record = new MoneyRequestRecords;

        $record->money_request_id = $updateMoneyRequest->id;
        $record->user_id          = $user->id;
        $record->action           = self::EDITED;

        $name  = $user->name;
        $email = $user->email;

        $details = "$name - ($email) Editó la solicitud, edición número $updateMoneyRequest->edit_count: ";

        $updatedCount = 0;

        foreach ($updateMoneyRequest->toArray() as $key => $newValue) {
            $oldValue = $oldData[$key];

            if ($oldValue != $newValue) {

                if ($label = $labels[$key] ?? false) {
                    $updatedCount++;
                    if ($key === 'amount') {
                        $oldValue = '$'.number_format($oldValue, 2);
                        $newValue = '$'.number_format($newValue, 2);
                    } elseif ($key === 'type') {
                        $oldValue = Type::getName($oldValue);
                        $newValue = Type::getName($newValue);
                    }

                    $oldValue = empty($oldValue) ? 'N/D' : $oldValue;
                    $newValue = empty($newValue) ? 'N/D' : $newValue;

                    $text = "\n- $label: $oldValue → $newValue";

                    $details .= $text;
                }
            }
        }

        if ($updatedCount == 0) {
            $details .= "\n- No se hicieron cambios.";
        }

        $record->details       = trim($details);
        $record->registered_at = $registered_at ?? now();
        $record->save();

        return $record;
    }
}
