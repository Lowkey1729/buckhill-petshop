<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\File
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $path
 * @property string $size
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static FileFactory factory($count = null, $state = [])
 * @method static Builder|File newModelQuery()
 * @method static Builder|File newQuery()
 * @method static Builder|File query()
 * @method static Builder|File whereCreatedAt($value)
 * @method static Builder|File whereId($value)
 * @method static Builder|File whereName($value)
 * @method static Builder|File wherePath($value)
 * @method static Builder|File whereSize($value)
 * @method static Builder|File whereType($value)
 * @method static Builder|File whereUpdatedAt($value)
 * @method static Builder|File whereUuid($value)
 *
 * @mixin Eloquent
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'type',
        'name',
        'path',
        'size',
    ];
}
