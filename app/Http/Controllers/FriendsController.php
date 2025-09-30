<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Mail\FriendRequestMail;
use App\Models\User;
use App\Models\UserFriendList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            'email' => 'required|string|email|exists:users,email',
        ]);

        $user = Auth::user();
        $friend = User::where('email', $request->email)->first();
        if ($friend->id === $user->id) {
            return redirect()->back()->with('error', 'Você não pode adicionar a si mesmo como amigo.');
        }

        $user->friends()->syncWithoutDetaching([
            $friend->id => [
                'status' => 'pending',
                'invited_by' => $user->id,
            ]
        ]);

        $url = route('friends.index');

        //Envia e-mail diretão sem background(mais demorado)
        Mail::to($friend->email)->send(new FriendRequestMail($user, $url));

        // Envia e-mail em background
        // SendMailJob::dispatch($friend->email, new FriendRequestMail($user, $url));

        return redirect()->route('friends.index')->with('success', 'Convite de amizade enviado com sucesso!');
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
            return redirect()->route('friends.index')->with('error', 'Solicitação de amizade não encontrada.');
        }

        // Atualiza status para 'blocked'
        $pivot->update(['status' => 'blocked']);

        return redirect()->route('friends.index')->with('success', 'Solicitação de amizade rejeitada com sucesso.');

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
            return redirect()->route('friends.index')->with('error', 'Solicitação de amizade não encontrada.');
        }

        // Atualiza status para 'blocked'
        $pivot->update(['status' => 'accepted']);

        return redirect()->route('friends.index')->with('success', 'Solicitação de amizade aceita com sucesso!');

    }
}
