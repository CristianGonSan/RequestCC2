<?php

namespace App\Models\Common;

use App\Enums\Common\ChangeLogEvent;
use App\Models\User;
use App\Support\DataBag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $subject_type
 * @property int $subject_id
 * @property int|null $user_id
 * @property ChangeLogEvent $event
 * @property array<array-key, mixed> $details
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $event_bs_color
 * @property-read string $event_label
 * @property-read Model|\Eloquent $subject
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChangeLog whereUserId($value)
 *
 * @mixin \Eloquent
 */
class ChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'event',
        'details',
    ];

    protected $casts = [
        'event'   => ChangeLogEvent::class,
        'details' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailsBag(): DataBag
    {
        return DataBag::make($this->details ?? []);
    }

    public function getEventLabelAttribute(): string
    {
        return $this->event->label();
    }

    public function getEventBsColorAttribute(): string
    {
        return $this->event->bootstrapColorClass();
    }

    public static function formatValue(mixed $value): string
    {
        if ($value === null) {
            return '—';
        }

        if (is_bool($value)) {
            return $value ? 'Sí' : 'No';
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        }

        return (string) $value;
    }

    /**
     * @return array<string, array{before: mixed, after: mixed}>
     */
    public static function getChangesArray(Model $model): array
    {
        $changes = $model->getChanges();
        unset($changes['created_at'], $changes['updated_at']);

        $fields = [];

        foreach ($changes as $attribute => $newValue) {
            $fields[$attribute] = [
                'before' => $model->getRawOriginal($attribute),
                'after'  => $newValue,
            ];
        }

        return $fields;
    }
}
