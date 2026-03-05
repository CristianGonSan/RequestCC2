<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property-read User|null $user
 * @method static Builder|BugReport newModelQuery()
 * @method static Builder|BugReport newQuery()
 * @method static Builder|BugReport query()
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $details
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BugReport whereCreatedAt($value)
 * @method static Builder|BugReport whereDetails($value)
 * @method static Builder|BugReport whereId($value)
 * @method static Builder|BugReport whereTitle($value)
 * @method static Builder|BugReport whereUpdatedAt($value)
 * @method static Builder|BugReport whereUserId($value)
 * @mixin \Eloquent
 */
class BugReport extends Model
{
    use HasFactory;

    protected $table = 'bug_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'details',
    ];

    /**
     * Get the user that owns the bug report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
