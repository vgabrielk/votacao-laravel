<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollVotes extends Model
{
    protected $fillable = [
        'poll_id',
        'option_id',
        'voter_id',
        'ip_address'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    public function pollOption()
    {
        return $this->belongsTo(PollOption::class, 'option_id');
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

}
