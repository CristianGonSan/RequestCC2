<?php

namespace App\Models;

use App\Enums\Files\FileExtensionSupport;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Extiende el modelo de Spatie para asociar un propietario al archivo y
 * para que las URLs generadas apunten siempre al MediaController en vez de
 * a la ruta física del disco. Esto permite controlar el acceso a los
 * archivos privados y mantener una única forma de resolverlos.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array<array-key, mixed> $manipulations
 * @property array<array-key, mixed> $custom_properties
 * @property array<array-key, mixed> $generated_conversions
 * @property array<array-key, mixed> $responsive_images
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $extension
 * @property-read FileExtensionSupport $extension_support
 * @property-read mixed $human_readable_size
 * @property-read Model|\Eloquent $model
 * @property-read mixed $original_url
 * @property-read mixed $preview_url
 * @property-read mixed $type
 * @property-read \App\Models\User|null $user
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> all($columns = ['*'])
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia newQuery()
 * @method static Builder<static>|CustomMedia ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomMedia whereUuid($value)
 * @mixin \Eloquent
 */
class CustomMedia extends Media
{
    protected $fillable = [
        'user_id',
    ];

    public function getExtensionSupportAttribute(): FileExtensionSupport
    {
        return FileExtensionSupport::fromExtension($this->extension);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwnedBy(?int $userId): bool
    {
        return $userId !== null && $this->user_id === $userId;
    }

    public function getUrl(string $conversionName = ''): string
    {
        return route('media.show', ['id' => $this->id, 'filename' => $this->file_name]);
    }

    public function getDownloadUrl(): string
    {
        return route('media.download', ['id' => $this->id, 'filename' => $this->file_name]);
    }
}
