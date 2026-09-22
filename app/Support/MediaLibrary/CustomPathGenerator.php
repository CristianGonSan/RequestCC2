<?php

namespace App\Support\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->basePath($media);
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->basePath($media).'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->basePath($media).'responsive-images/';
    }

    protected function basePath(Media $media): string
    {
        $modelType = str($media->model_type)
            ->classBasename()
            ->snake()
            ->plural()
            ->toString();

        return \sprintf(
            'media/%s/%s/%s/%s/',
            $modelType,
            $media->created_at->format('Y_m'),
            $media->model_id,
            $media->getKey()
        );
    }
}
