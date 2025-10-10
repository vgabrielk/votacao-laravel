<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'is_private'
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'room_participants')
                    ->withPivot(['joined_at', 'last_read_at'])
                    ->withTimestamps();
    }

    public function roomParticipants(): HasMany
    {
        return $this->hasMany(RoomParticipant::class);
    }
}
