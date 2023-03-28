<?php

namespace App\Services\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self success()
 * @method static self failed()
 * */
class ApiResponse extends Enum
{
    public static function values(): array
    {
        return [
            'success' => 1,
            'failed' => 0,
        ];
    }
}
