<?php

namespace App\Services\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self user()
 * @method static self admin()
 * */
final class UserType extends Enum
{
    public static function values(): array
    {
        return [
            'user' => 0,
            'admin' => 1,
        ];
    }
}
