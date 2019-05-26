<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscordGuild extends Model
{
    protected $increments = false;
    protected $guarded = [];

    public function emojis()
    {
        return $this->hasMany(DiscordEmoji::class)->orderBy('name', 'DESC');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getIconLinkAttribute()
    {
        $url = 'https://cdn.discordapp.com/icons/' . $this->id . '/' . $this->icon . '.png';

        return $url;
    }
}
