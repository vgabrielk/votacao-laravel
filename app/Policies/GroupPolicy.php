<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    public function canAddOtherUsers(User $user, Group $group): bool
    {
        return $group->creator_id === $user->id;
    }
}
