<?php

namespace App;

use Illuminate\Support\Str;

trait NormalizesAttributes
{
    public static function bootNormalizesAttributes(): void
    {
        static::saving(function ($model) {
            foreach ($model->getAttributes() as $key => $value) {
                if (is_string($value) && in_array($key, $model->normalizable ?? [])) {
                    $model->$key = Str::lower($value); // o trim, ucfirst, etc.
                }
            }
        });
    }
}
