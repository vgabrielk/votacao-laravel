<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = [
        'text'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

}
