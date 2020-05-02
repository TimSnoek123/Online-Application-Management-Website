<?php

namespace App;

use Illuminate\Support\Facades\Http;

use Illuminate\Database\Eloquent\Model;

class CommandLink extends Model
{
    private const link_type = [
        0 => 'get',
        1 => 'send'
    ];

    protected $fillable = [
        'name', 'link', 'link_type'
    ];

    public function getLinkType()
    {
        return self::link_type[$this->attributes['link_type']];
    }
}
