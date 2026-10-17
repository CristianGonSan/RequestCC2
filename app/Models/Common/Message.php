<?php

namespace App\Models\Common;

use App\Contracts\Common\HasMessages;
use App\Jobs\SendEmail;
use App\Mail\Common\NewMessageNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use LogicException;

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

    public function sendEmail(): void
    {
        $messageable = $this->messageable;

        if (! $messageable instanceof HasMessages) {
            throw new LogicException('The messageable model must implement HasMessages.');
        }

        if (! method_exists($messageable, 'user')) {
            throw new LogicException('The messageable model must define a user relation.');
        }

        $owner = $messageable->getRelationValue('user');

        if (! $owner instanceof User) {
            throw new LogicException('The messageable model must have a user relation.');
        }

        $sender = $this->user;

        if (! $sender instanceof User) {
            throw new LogicException('The message must have a user relation.');
        }

        $createdAt = $this->created_at;

        if ($createdAt === null) {
            throw new LogicException('Cannot send a message notification before the message is persisted.');
        }

        SendEmail::dispatch(
            [$owner->email],
            new NewMessageNotification(
                message: $this->message,
                sender: "{$sender->name} ({$sender->email})",
                messageDate: $createdAt->format('d/m/Y h:i:s a'),
                messageableUrl: $messageable->messageUrl(),
                messageableLabel: "{$messageable->messageLabel()} #{$messageable->getKey()}",
            ),
        );
    }
}
