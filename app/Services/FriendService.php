<?php

namespace App\Services;


use App\Jobs\SendMailJob;

use App\Models\User;
use App\Models\UserFriendList;
use App\Mail\FriendRequestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FriendService
{
    /**
     * Obter dados do dashboard de amigos
     */
    public function getUserFriendsData(User $user): ServiceResult
    {
        try {
            $friendsSent = $user->friends()->get();

            $friendsReceived = $user->friendsReceived()->get();

            $allFriends = $friendsSent->merge($friendsReceived);

            $data = [
                'user' => $user,
                'friends' => $allFriends
            ];

            return ServiceResult::success('Dados carregados com sucesso!', $data);

        } catch (\Exception $e) {
            Log::error('Erro ao carregar dados de amigos', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return ServiceResult::error('Erro ao carregar dados de amigos. Tente novamente.');
        }
    }

    /**
     * Enviar solicitação de amizade
     */
    public function sendFriendRequest(string $email): ServiceResult
    {
        try {
            $user = Auth::user();
            $friend = User::where('email', $email)->first();

            if ($friend->id === $user->id) {
                return ServiceResult::error('Você não pode adicionar a si mesmo como amigo.');
            }

            if ($this->friendshipRequestExists($user, $friend)) {
                return ServiceResult::error('Solicitação de amizade já enviada ou já são amigos.');
            }

            $this->createFriendRequest($user, $friend);

            $this->sendFriendRequestEmail($user, $friend);

            return ServiceResult::success('Convite de amizade enviado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao enviar solicitação de amizade', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return ServiceResult::error('Erro inesperado. Tente novamente em alguns instantes.');
        }
    }

    /**
     * Rejeitar solicitação de amizade
     */
    public function rejectFriendRequest(int $pivotId): ServiceResult
    {
        try {
            $user = Auth::user();

            $pivot = $this->findFriendRequest($pivotId, $user);

            if (!$pivot) {
                return ServiceResult::error('Solicitação de amizade não encontrada.');
            }

            // Atualizar status para 'blocked'
            $pivot->update(['status' => 'blocked']);

            return ServiceResult::success('Solicitação de amizade rejeitada com sucesso.');

        } catch (\Exception $e) {
            Log::error('Erro ao rejeitar solicitação de amizade', [
                'pivot_id' => $pivotId,
                'error' => $e->getMessage()
            ]);

            return ServiceResult::error('Erro inesperado. Tente novamente em alguns instantes.');
        }
    }

    /**
     * Aceitar solicitação de amizade
     */
    public function acceptFriendRequest(int $pivotId): ServiceResult
    {
        try {
            $user = Auth::user();

            $pivot = $this->findFriendRequest($pivotId, $user);

            if (!$pivot) {
                return ServiceResult::error('Solicitação de amizade não encontrada.');
            }

            $pivot->update(['status' => 'accepted']);

            return ServiceResult::success('Solicitação de amizade aceita com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao aceitar solicitação de amizade', [
                'pivot_id' => $pivotId,
                'error' => $e->getMessage()
            ]);

            return ServiceResult::error('Erro inesperado. Tente novamente em alguns instantes.');
        }
    }

    /**
     * Verificar se já existe solicitação de amizade
     */
    private function friendshipRequestExists(User $user, User $friend): bool
    {
        return UserFriendList::where(function ($query) use ($user, $friend) {
            $query->where('user_id', $user->id)
                  ->where('friend_id', $friend->id);
        })->orWhere(function ($query) use ($user, $friend) {
            $query->where('user_id', $friend->id)
                  ->where('friend_id', $user->id);
        })->exists();
    }

    /**
     * Criar solicitação de amizade
     */
    private function createFriendRequest(User $user, User $friend): void
    {
        $user->friends()->syncWithoutDetaching([
            $friend->id => [
                'status' => 'pending',
                'invited_by' => $user->id,
            ]
        ]);
    }

    /**
     * Enviar email de solicitação de amizade
     */
    private function sendFriendRequestEmail(User $user, User $friend): void
    {
        $url = route('friends.index');

        // Enviar email diretamente (mais rápido para desenvolvimento)

// Mail::to($friend->email)->send(new FriendRequestMail($user, $url));


        // Para produção, usar queue:

SendMailJob::dispatch($friend->email, new FriendRequestMail($user, $url));

    }

    /**
     * Encontrar solicitação de amizade
     */
    private function findFriendRequest(int $pivotId, User $user): ?UserFriendList
    {
        return UserFriendList::where('id', $pivotId)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('friend_id', $user->id);
            })
            ->first();
    }
}
