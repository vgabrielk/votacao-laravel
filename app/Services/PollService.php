<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Poll;
use Illuminate\Support\Facades\Auth;

class PollService
{
    public function createPoll(Group $group, $request): ServiceResult
    {
        $poll = $group->polls()->create(array_merge($request->all(), [
            'creator_id' => Auth::user()->id
        ]));

        foreach ($request->options as $optionText) {
            $poll->options()->create([
                'text' => $optionText
            ]);
        }

        return ServiceResult::success('Enquete criada com sucesso!');

    }

    public function listPolls(Group $group): ServiceResult
    {
        $polls = $group->polls()
            ->with(['creator', 'options'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ServiceResult::success('Enquetes carregadas com sucesso!', ['polls' => $polls]);
    }

    public function getPoll(Group $group, $pollId): ServiceResult
    {
        $poll = $group->polls()
            ->with(['creator', 'options'])
            ->find($pollId);

        if (!$poll) {
            return ServiceResult::error('Enquete não encontrada!');
        }

        return ServiceResult::success('Enquete carregada com sucesso!', ['poll' => $poll]);
    }

    public function publishPoll(Group $group, $pollId): ServiceResult
    {
        $poll = $group->polls()->find($pollId);

        if (!$poll) {
            return ServiceResult::error('Enquete não encontrada!');
        }

        if ($poll->status !== 'draft') {
            return ServiceResult::error('Apenas enquetes em rascunho podem ser publicadas!');
        }

        $poll->update(['status' => 'open']);

        return ServiceResult::success('Enquete publicada com sucesso!');
    }

    public function closePoll(Group $group, $pollId): ServiceResult
    {
        $poll = $group->polls()->find($pollId);

        if (!$poll) {
            return ServiceResult::error('Enquete não encontrada!');
        }

        if ($poll->status !== 'open') {
            return ServiceResult::error('Apenas enquetes abertas podem ser encerradas!');
        }

        $poll->update(['status' => 'closed']);

        return ServiceResult::success('Enquete encerrada com sucesso!');
    }

    public function votePoll(Group $group, $pollId, $optionId, $userId): ServiceResult
    {
        $poll = $group->polls()->find($pollId);

        if (!$poll) {
            return ServiceResult::error('Enquete não encontrada!');
        }

        if ($poll->status !== 'open') {
            return ServiceResult::error('Esta enquete não está aberta para votação!');
        }

        // Verificar se a opção pertence à enquete
        $option = $poll->options()->find($optionId);
        if (!$option) {
            return ServiceResult::error('Opção inválida!');
        }

        // Verificar se o usuário já votou (se não permitir múltipla escolha)
        if (!$poll->allow_multiple) {
            $existingVote = $poll->votes()->where('voter_id', $userId)->first();
            if ($existingVote) {
                return ServiceResult::error('Você já votou nesta enquete!');
            }
        }

        // Criar o voto
        $poll->votes()->create([
            'option_id' => $optionId,
            'voter_id' => $userId,
            'ip_address' => request()->ip()
        ]);

        return ServiceResult::success('Voto registrado com sucesso!');
    }

}
