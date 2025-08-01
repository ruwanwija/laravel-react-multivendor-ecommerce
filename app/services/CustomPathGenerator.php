<?php

namespace App\Services;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return md5($media->id . config('app.key')) . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return md5($media->id . config('app.key')) . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return md5($media->id . config('app.key')) . '/responsive-images/';
    }
}