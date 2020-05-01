<?php

namespace App;

use Illuminate\Support\Facades\Http;

use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    protected $fillable = [
        'authUrl', 'tokenUrl', 'clientId', 'scopes' => 'array', 'redirectUri', 'clientSecret', 'responseMode', 'responseType', 'grantType', 
    ];

    public function SourceCompany(){
       return $this->belongsTo(SourceCompany::class);
    }
}
