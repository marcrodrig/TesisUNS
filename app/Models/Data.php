<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserID',
        'UserScreenName',
        'UserName',
        'UserCreatedAt',
        'UserDescription',
        'UserDescriptionLength',
        'UserFollowersCount',
        'UserFriendsCount',
        'UserLocation',
        'AvgHashtag',
        'AvgURLCount',
        'AvgMention',
        'AvgRetweet',
        'AvgFavCount',
        'TweetCount',
        'CurrentDate',
        'bot'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'UserCreatedAt' => 'datetime',
        'CurrentDate' => 'datetime',
    ];

}
