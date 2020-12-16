<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthUser extends Model
{
    protected $fillable = ['email', 'provider', 'provider_id'];
}
