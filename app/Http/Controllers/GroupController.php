<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $groups = Group::with('members')
            ->where(function ($q) use ($userId) {
                $q->where('creator_id', $userId)
                  ->orWhereHas('members', function ($m) use ($userId) {
                      $m->where('user_id', $userId);
                  });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('groups.groups', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request)
    {
        $user = Auth::user();
        $group = new Group($request->validated());
        $group->creator()->associate($user);
        $group->save();
        return redirect()->route('groups.index')->with('success', 'Grupo criado com sucesso! Você já pode começar a adicionar membros.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Grupo excluído com sucesso!');
    }


    public function addMember(Request $request, Group $group)
    {

$this->authorize('canManageGroup', $group);


        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (! $user) {
                return redirect()
                    ->route('groups.index')
                    ->with('error', 'Usuário não encontrado. Verifique o e-mail informado e tente novamente.');
            }

            $group->members()->attach($user->id, [
                'role'       => 'member',
                'status'     => 'active',
                'invited_by' => Auth::user()->id,
                'group_id' => $group->id,

            ]);

            return redirect()
                ->route('groups.index')
                ->with('success', 'Membro adicionado ao grupo com sucesso!');
        } catch (QueryException $e) {
            Log::error('Erro ao adicionar membro ao grupo', [
                'group_id' => $group->id,
                'email'    => $request->email,
                'error'    => $e->getMessage(),
            ]);

            return redirect()
                ->route('groups.index')
                ->with('error', 'Erro ao adicionar membro ao grupo. O usuário pode já estar no grupo.');
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao adicionar membro', [
                'error' => $e->getMessage(),
            ]);
            return redirect()
                ->route('groups.index')
                ->with('error', 'Erro inesperado. Tente novamente em alguns instantes.');
        }
    }

}
