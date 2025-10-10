<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rooms = $user->rooms()->with(['participants', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])->get();

        return view('chat.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        $user = Auth::user();
        
        // Check if user is participant of the room
        if (!$room->participants()->where('user_id', $user->id)->exists()) {
            abort(403, 'Você não tem acesso a esta sala.');
        }

        $messages = $room->messages()->with('user')->latest()->paginate(50);
        $participants = $room->participants()->with('user')->get();

        return view('chat.room', compact('room', 'messages', 'participants'));
    }

    public function direct(User $friend)
    {
        $user = Auth::user();
        
        // Verificar se são amigos (em qualquer direção)
        $areFriends = $user->friends()->where('users.id', $friend->id)->exists() ||
                      $friend->friends()->where('users.id', $user->id)->exists();
        
        if (!$areFriends) {
            abort(403, 'Você só pode conversar com seus amigos.');
        }

        // Buscar ou criar sala privada entre os dois usuários
        $room = Room::whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('participants', function($query) use ($friend) {
                $query->where('user_id', $friend->id);
            })
            ->where('is_private', true)
            ->first();

        // Se não existe, criar
        if (!$room) {
            $room = Room::create([
                'name' => $user->name . ' & ' . $friend->name,
                'description' => 'Chat privado',
                'created_by' => $user->id,
                'is_private' => true,
            ]);

            // Adicionar ambos como participantes
            $room->participants()->attach([
                $user->id => ['joined_at' => now()],
                $friend->id => ['joined_at' => now()],
            ]);
        }

        return view('chat.direct', compact('room', 'friend'));
    }
}
