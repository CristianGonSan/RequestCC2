<?php

namespace App\Models\Common;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $messageable_id
 * @property int $user_id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $messageable_type
 * @property-read Model|\Eloquent $messageable
 * @property-read User $user
 *
 * @method static Builder<static>|Message newModelQuery()
 * @method static Builder<static>|Message newQuery()
 * @method static Builder<static>|Message query()
 * @method static Builder<static>|Message whereCreatedAt($value)
 * @method static Builder<static>|Message whereId($value)
 * @method static Builder<static>|Message whereMessage($value)
 * @method static Builder<static>|Message whereMessageableId($value)
 * @method static Builder<static>|Message whereMessageableType($value)
 * @method static Builder<static>|Message whereUpdatedAt($value)
 * @method static Builder<static>|Message whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'messageable_id',
        'messageable_type',
        'user_id',
        'message',
    ];

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
