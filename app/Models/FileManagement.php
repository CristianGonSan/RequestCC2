<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $request_id
 * @property int $user_id
 * @property string $file_path
 * @property string|null $original_name
 * @property string|null $mime_type
 * @property int|null $size
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read RequestModel $request
 * @property-read User $user
 * @method static Builder|FileManagement newModelQuery()
 * @method static Builder|FileManagement newQuery()
 * @method static Builder|FileManagement query()
 * @method static Builder|FileManagement whereCreatedAt($value)
 * @method static Builder|FileManagement whereFilePath($value)
 * @method static Builder|FileManagement whereId($value)
 * @method static Builder|FileManagement whereMimeType($value)
 * @method static Builder|FileManagement whereOriginalName($value)
 * @method static Builder|FileManagement whereRequestId($value)
 * @method static Builder|FileManagement whereSize($value)
 * @method static Builder|FileManagement whereUpdatedAt($value)
 * @method static Builder|FileManagement whereUserId($value)
 * @mixin \Eloquent
 */
class FileManagement extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'file_management';

    // Campos que se pueden llenar de forma masiva
    protected $fillable = [
        'request_id',
        'user_id',
        'file_path',
        'original_name',
        'mime_type',
        'size',
    ];

    // Relación con la tabla 'requests'
    public function request(): BelongsTo
    {
        return $this->belongsTo(RequestModel::class);
    }

    // Relación con la tabla 'users'
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

