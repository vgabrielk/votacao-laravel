<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    public function addMember(string $email, Group $group): ServiceResult
    {
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return ServiceResult::error('Usuário não encontrado. Verifique o e-mail informado e tente novamente.');
            }

            $group->members()->attach($user->id, [
                'role'       => 'member',
                'status'     => 'active',
                'invited_by' => Auth::user()->id,
                'group_id' => $group->id,

            ]);
            return ServiceResult::success('Membro adicionado ao grupo com sucesso!');
        } catch (QueryException $e) {
            return ServiceResult::error('Erro ao adicionar membro ao grupo. O usuário pode já estar no grupo.');
        } catch (\Exception $e) {
            return ServiceResult::error('Erro inesperado. Tente novamente em alguns instantes.');
        }
    }
}
