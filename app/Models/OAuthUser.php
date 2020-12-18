<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OAuthUser
 *
 * @property int $id
 * @property string $email
 * @property string $provider
 * @property string $provider_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OAuthUser extends Model
{
    protected $fillable = ['email', 'provider', 'provider_id'];
}
