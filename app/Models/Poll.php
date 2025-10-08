<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    //

    protected $fillable = [
        'title',
        'description',
        'type',
        'anonymus',
        'allow_multiple',
        'status',
        'start_at',
        'end_at',
        'creator_id',
    ];


    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function options()
    {
        return $this->hasMany(PollOption::class);
    }
    public function votes()
    {
        return $this->hasMany(PollVotes::class);
    }

}
