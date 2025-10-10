<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Room;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Room $room)
    {
        $user = Auth::user();
        
        if (!$room->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $messages = $room->messages()
            ->with('user')
            ->latest()
            ->paginate(50);

        return response()->json($messages);
    }

    public function store(Request $request, Room $room)
    {
        \Log::info('📥 MessageController::store - Iniciando', [
            'room_id' => $room->id,
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        $user = Auth::user();
        
        if (!$room->participants()->where('user_id', $user->id)->exists()) {
            \Log::warning('❌ Usuário não é participante da sala', [
                'user_id' => $user->id,
                'room_id' => $room->id
            ]);
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        \Log::info('✅ Usuário é participante da sala');

        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'type' => 'string|in:text,image,file',
                'metadata' => 'nullable|array'
            ]);
            
            \Log::info('✅ Dados validados com sucesso', $validated);
        } catch (\Exception $e) {
            \Log::error('❌ Erro na validação', [
                'error' => $e->getMessage(),
                'errors' => $e->errors ?? []
            ]);
            throw $e;
        }

        $message = Message::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'content' => $request->content,
            'type' => $request->type ?? 'text',
            'metadata' => $request->metadata
        ]);

        \Log::info('✅ Mensagem criada no banco', [
            'message_id' => $message->id,
            'content' => $message->content
        ]);

        $message->load('user');

        \Log::info('📡 Enviando broadcast...');

        // Broadcast the message
        try {
            broadcast(new MessageSent($message, $room))->toOthers();
            \Log::info('✅ Broadcast enviado com sucesso');
        } catch (\Exception $e) {
            \Log::error('❌ Erro no broadcast', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        \Log::info('✅ Retornando resposta de sucesso');

        return response()->json([
            'success' => true,
            'message' => $message
        ], 201);
    }

    public function markAsRead(Room $room)
    {
        $user = Auth::user();
        
        if (!$room->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $room->participants()
            ->where('user_id', $user->id)
            ->update(['last_read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
