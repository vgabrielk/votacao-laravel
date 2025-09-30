<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'visibility',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Members of this group
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id')
                    ->withTimestamps();
    }

    public function polls(){
        return $this->hasMany(Poll::class);
    }
}
