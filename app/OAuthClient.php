<?php

namespace App;

use Illuminate\Support\Facades\Http;

use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    protected $fillable = [
        'authUrl', 'tokenUrl', 'clientId', 'scopes' => 'array', 'redirectUri', 'name', 'clientSecret', 'responseMode', 'responseType', 'grantType', 
    ];


}
