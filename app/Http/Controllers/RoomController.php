<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rooms = $user->rooms()->with(['participants', 'messages' => function($query) {
            $query->latest()->limit(1);
        }])->get();

        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
            'participants' => 'array',
            'participants.*' => 'exists:users,id'
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $room = Room::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $user->id,
                'is_private' => $request->is_private ?? false,
            ]);

            // Add creator as participant
            $room->participants()->attach($user->id, [
                'joined_at' => now(),
                'last_read_at' => now()
            ]);

            // Add other participants
            if ($request->participants) {
                $participants = collect($request->participants)->mapWithKeys(function ($userId) {
                    return [$userId => ['joined_at' => now(), 'last_read_at' => now()]];
                });
                $room->participants()->attach($participants);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'room' => $room->load('participants')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar sala: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Room $room)
    {
        $user = Auth::user();
        
        if (!$room->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $room->load(['participants', 'messages.user']);
        
        return response()->json($room);
    }

    public function addParticipant(Request $request, Room $room)
    {
        $user = Auth::user();
        
        if ($room->created_by !== $user->id) {
            return response()->json(['error' => 'Apenas o criador pode adicionar participantes'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $room->participants()->syncWithoutDetaching([
            $request->user_id => [
                'joined_at' => now(),
                'last_read_at' => now()
            ]
        ]);

        return response()->json(['success' => true]);
    }

    public function removeParticipant(Request $request, Room $room)
    {
        $user = Auth::user();
        
        if ($room->created_by !== $user->id && $request->user_id !== $user->id) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $room->participants()->detach($request->user_id);

        return response()->json(['success' => true]);
    }
}
