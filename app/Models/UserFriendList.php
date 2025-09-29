<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriendList extends Model
{
    //

    protected $table = 'user_friend_list';

    protected $fillable = [
        'status',
        'friend_id',
        'invited_by',
        'user_id'
    ];



}
