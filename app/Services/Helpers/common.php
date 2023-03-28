<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

if (! function_exists('generate_uuid')) {
    function generate_uuid(Model $model): Ramsey\Uuid\UuidInterface
    {
        $uuid = Str::uuid();
        if ($model::query()->where('uuid', $uuid)->first()) {
            generate_uuid($model);
        }

        return $uuid;
    }
}
