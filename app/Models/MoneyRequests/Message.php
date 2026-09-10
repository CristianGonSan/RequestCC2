<?php

namespace App\Models\MoneyRequests;

use App\Models\MoneyRequests\MoneyRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $money_request_id
 * @property int $user_id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MoneyRequest|null $request
 * @property-read User $user
 * @method static Builder<static>|Message newModelQuery()
 * @method static Builder<static>|Message newQuery()
 * @method static Builder<static>|Message query()
 * @method static Builder<static>|Message whereCreatedAt($value)
 * @method static Builder<static>|Message whereId($value)
 * @method static Builder<static>|Message whereMessage($value)
 * @method static Builder<static>|Message whereMoneyRequestId($value)
 * @method static Builder<static>|Message whereUpdatedAt($value)
 * @method static Builder<static>|Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'money_request_id',
        'user_id',
        'message',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(MoneyRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
