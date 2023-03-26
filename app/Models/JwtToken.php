<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JwtToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unique_id',
        'token_title',
        'restrictions',
        'permissions',
        'expires_at',
        'last_used_at',
        'refreshed_at',
    ];

    protected $casts = [
        'restrictions' => 'array',
        'permissions' => 'array',
    ];

    protected $hidden = ['id', 'user_id', 'restrictions', 'permissions'];

    /**
     * @return BelongsTo<User, JwtToken>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function findToken(string $token): object|null
    {
        return static::where('unique_id', hash('sha256', $token))->first();
    }
}
