<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function canView(User $user, Poll $poll)
    {

    }
}
