<?php

namespace App\Models;

use App\Models\Traits\DiffForHumansTimestampAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $status 0: order is being processed, 1: order processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 * @property int $amount
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @property string $uuid
 * @property-read mixed $created_date
 * @property-read mixed $updated_date
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUuid($value)
 * @property-read \App\Models\Product|null $product
 * @property string $order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrder($value)
 * @property-read \Illuminate\Support\Collection $ordered_products
 */
class Order extends Model
{
    use HasFactory;
    use DiffForHumansTimestampAttributes;

    protected $fillable = [
        'order',
        'status'
    ];

    protected $casts = [
        'order' => 'string',
        'status' => 'integer',
    ];

    protected $appends = [
        'created_date',
        'updated_date',
        'ordered_products'
    ];

    /**
     * Related users for current order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return products that user has ordered
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOrderedProductsAttribute()
    {
        $ids = json_decode($this->order);

        return Product::whereIn('id', $ids)->get(['slug', 'name']);
    }
}
