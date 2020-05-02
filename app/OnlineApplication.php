<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * OnlineApplication
 *
 * @mixin Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $thumbnail
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OnlineApplication whereUpdatedAt($value)
 */
class OnlineApplication extends Model
{


    protected $fillable = [
        'name', 'thumbnail', 'get_links' => 'array', 'post_links' => 'array'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function SourceCompany()
    {
        return $this->hasOne(SourceCompany::class);
    }
}
