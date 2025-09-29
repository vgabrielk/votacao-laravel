<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }


    public function friends()
    {
        return $this->belongsToMany(User::class, 'user_friend_list', 'user_id', 'friend_id')
                    ->withPivot('status', 'invited_by')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }
    public function friendRequests()
    {
        return $this->belongsToMany(
            User::class,           // quem enviou a solicitação
            'user_friend_list',    // tabela pivô
            'friend_id',           // chave local: quem recebe a solicitação
            'user_id'              // chave do outro usuário: quem enviou
        )
        ->withPivot('id','status', 'invited_by')
        ->wherePivot('status', 'pending')  // apenas solicitações pendentes
        ->withTimestamps();
    }

    public function friendsReceived()
{
    return $this->belongsToMany(User::class, 'user_friend_list', 'friend_id', 'user_id')
                ->withPivot('status', 'invited_by')
                ->wherePivot('status', 'accepted');
}

    /**
     * Groups created by this user
     */
    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'creator_id');
    }

    /**
     * Groups where this user is a member
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id')
                    ->withTimestamps();
    }
}
