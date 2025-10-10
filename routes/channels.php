<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Chat room channels
Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    return $user->rooms()->where('rooms.id', $roomId)->exists();
});
