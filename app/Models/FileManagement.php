<?php

namespace App\Models;

use App\Enums\Files\FileExtensionSupport;
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
 * @property-read string $extension
 * @property-read FileExtensionSupport $extension_support
 * @property-read float $file_size_in_k_b
 * @property-read float $file_size_in_m_b
 * @property-read string $human_readable_size
 * @mixin \Eloquent
 */
class FileManagement extends Model
{
    use HasFactory;

    protected $table = 'file_management';

    protected $fillable = [
        'request_id',
        'user_id',
        'file_path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(RequestModel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    public function getFileSizeInMBAttribute(): float
    {
        return $this->size / (1024 * 1024);
    }

    public function getFileSizeInKBAttribute(): float
    {
        return $this->size / 1024;
    }

    public function getExtensionSupportAttribute(): FileExtensionSupport
    {
        return FileExtensionSupport::fromExtension($this->extension);
    }

    public function getHumanReadableSizeAttribute(): string
    {
        if ($this->size === null) {
            return '—';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($this->size, 0);
        $pow = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $pow = min($pow, \count($units) - 1);

        $bytes /= (1024 ** $pow);

        return round($bytes, $pow > 0 ? 1 : 0).' '.$units[$pow];
    }
}
