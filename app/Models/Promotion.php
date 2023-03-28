<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Database\Factories\PromotionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Promotion
 *
 * @property int $id
 * @property string $uuid
 * @property string $content
 * @property mixed|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static PromotionFactory factory($count = null, $state = [])
 * @method static Builder|Promotion newModelQuery()
 * @method static Builder|Promotion newQuery()
 * @method static Builder|Promotion query()
 * @method static Builder|Promotion whereContent($value)
 * @method static Builder|Promotion whereCreatedAt($value)
 * @method static Builder|Promotion whereId($value)
 * @method static Builder|Promotion whereMetadata($value)
 * @method static Builder|Promotion whereUpdatedAt($value)
 * @method static Builder|Promotion whereUuid($value)
 *
 * @mixin Eloquent
 */
class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'metadata',
        'content',
    ];
}
