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
}
