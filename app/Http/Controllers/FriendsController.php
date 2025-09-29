<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFriendList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function index()
    {
       $user = Auth::user();

    // Pega os amigos que o usuário enviou
    $friendsSent = $user->friends()->get();

    // Pega os amigos que enviaram amizade ao usuário
    $friendsReceived = $user->friendsReceived()->get();

    // Une as duas coleções
    $allFriends = $friendsSent->merge($friendsReceived);

    return view('friends.friends', [
        'user' => $user,
        'friends' => $allFriends
    ]);

    }

    public function create()
    {
        return view('friends.create');
    }

    public function addFriend(Request $request)
    {

        $request->validate([
            'email' => 'string|email',
        ]);

        $user = Auth::user();
        $friend = User::select('email', 'id')->where('email', $request->email)->first();

        $user->friends()->attach($friend->id, [
            'status' => 'pending',
            'invited_by' => $user->id,
        ]);

        return redirect()->route('friends.index')->with('success', 'Invited');
    }

    public function rejectFriend($pivotId)
    {
        $user = Auth::user();

        $pivot = UserFriendList::where('id', $pivotId)
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                              ->orWhere('friend_id', $user->id);
                    })
                    ->first();

        if (!$pivot) {
            return redirect()->route('friends.index')->with('error', 'Solicitação não encontrada.');
        }

        // Atualiza status para 'blocked'
        $pivot->update(['status' => 'blocked']);

        return redirect()->route('friends.index')->with('success', 'Solicitação rejeitada!');

    }
    public function acceptFriend($pivotId)
    {
        $user = Auth::user();

        $pivot = UserFriendList::where('id', $pivotId)
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                              ->orWhere('friend_id', $user->id);
                    })
                    ->first();

        if (!$pivot) {
            return redirect()->route('friends.index')->with('error', 'Solicitação não encontrada.');
        }

        // Atualiza status para 'blocked'
        $pivot->update(['status' => 'accepted']);

        return redirect()->route('friends.index')->with('success', 'Solicitação de amizade confirmada!');

    }
}
