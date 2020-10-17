<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Image
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $path
 * @property string $alias CDN alias
 * @property int $is_main
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Model|\Eloquent $images
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image modelImages($type, $id)
 */
class Image extends Model
{
    use HasFactory;

    const DEFAULT_PRODUCT_IMAGE = 'https://res.cloudinary.com/dwcqlqa5y/image/upload/w_1000,ar_16:9,c_fill,g_auto,e_sharpen/v1601644770/angry_emilia.jpg';
    const DEFAULT_SLIDER_IMAGE = 'https://res.cloudinary.com/dwcqlqa5y/image/upload/v1601644881/smiling_madara.jpg';

    protected $fillable = ['model_type', 'model_id', 'is_main', 'path', 'alias'];

    /**
     * Relation for Slide and Product models
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function images()
    {
        return $this->morphTo();
    }

    /**
     * Return images list by model type and model $id
     *
     * @param $query
     * @param $type
     * @param $id
     * @return mixed
     */
    public function scopeModelImages($query, $type, $id)
    {
        return $query->where('model_type', $type)->where('model_id', $id);
    }
}
