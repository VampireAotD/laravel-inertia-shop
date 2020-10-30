<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\UserOrder
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrder whereUserId($value)
 * @mixin \Eloquent
 */
class UserOrder extends Pivot
{
    use HasFactory;

    protected $table = 'user_orders';
}
