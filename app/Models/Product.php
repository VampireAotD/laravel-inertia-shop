<?php

namespace App\Models;

use App\Models\Traits\DiffForHumansTimestampAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $price
 * @property int $amount
 * @property string|null $description
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereViews($value)
 * @mixin \Eloquent
 * @property-read string $count_amount
 * @property-read mixed $created_date
 * @property-read string $excerpt
 * @property-read \Parsedown|string $html_description
 * @property-read mixed|string $main_image_path
 * @property-read mixed $updated_date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 */
class Product extends Model
{
    use HasFactory;
    use DiffForHumansTimestampAttributes;

    const PRODUCTS_FOLDER = 'products';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'amount',
        'description'
    ];

    protected $appends = [
        'created_date',
        'updated_date',
        'html_description',
        'excerpt',
        'count_amount',
        'main_image_path'
    ];

    /**
     * Related categories for current product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, ProductCategory::class)->withTimestamps();
    }

    /**
     * Related orders for current product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
//        return $this->belongsToMany(Order::class);// TODO : make this work
    }

    /**
     * Related images for current product
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'model')->orderByDesc('is_main');
    }

    /**
     * Related users that ordered this product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * XSS clean product description
     *
     * @return \Parsedown|string
     */
    public function getHtmlDescriptionAttribute()
    {
        return parsedown($this->description, $this->description);
    }

    /**
     * Short text from product description
     *
     * @return string
     */
    public function getExcerptAttribute()
    {
        return Str::limit($this->html_description, 50);
    }

    /**
     * Return plural value for current product amount
     *
     * @return string
     */
    public function getCountAmountAttribute()
    {
        return Str::plural('item', $this->amount);
    }

    /**
     * Return main image path for current product
     *
     * If there is no image, return default image from Cloudinary storage
     *
     * @return mixed|string
     */
    public function getMainImagePathAttribute()
    {
        return $this->images->where('is_main', 1)->first()->path ?? Image::DEFAULT_PRODUCT_IMAGE;
    }
}
