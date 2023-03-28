<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Services\JWT\Traits\HasApiTokens;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * App\Models\User
 *
 * @property mixed $uuid
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, JwtToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUuid($value)
 *
 * @mixin Eloquent
 *
 * @property string $first_name
 * @property string $last_name
 * @property int $is_admin
 * @property string $avatar
 * @property string $address
 * @property string $phone_number
 * @property int $is_marketing
 * @property string|null $last_login_at
 *
 * @method static Builder|User whereAddress($value)
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereIsAdmin($value)
 * @method static Builder|User whereIsMarketing($value)
 * @method static Builder|User whereLastLoginAt($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User wherePhoneNumber($value)
 *
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'uuid',
        'first_name',
        'last_name',
        'is_admin',
        'avatar',
        'address',
        'phone_number',
        'is_marketing',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function generateUUID(): string
    {
        $uuid = Str::uuid();
        if (self::query()->where('uuid', $uuid)->first()) {
            self::generateUUID();
        }

        return $uuid;
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = self::generateUUID();
        });
    }
}
