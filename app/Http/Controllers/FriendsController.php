<?php

namespace App\Http\Controllers;

use App\Services\FriendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function __construct(
        private FriendService $friendService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $result = $this->friendService->getUserFriendsData($user);

        if (!$result->success) {
            return redirect()->back()->with('error', $result->message);
        }

        return view('friends.friends', $result->data);
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

        $result = $this->friendService->sendFriendRequest($request->email);

        return redirect()->route('friends.index')
            ->with($result->success ? 'success' : 'error', $result->message);
    }

    public function rejectFriend($pivotId)
    {
        $result = $this->friendService->rejectFriendRequest($pivotId);

        return redirect()->route('friends.index')
            ->with($result->success ? 'success' : 'error', $result->message);
    }

    public function acceptFriend($pivotId)
    {
        $result = $this->friendService->acceptFriendRequest($pivotId);

        return redirect()->route('friends.index')
            ->with($result->success ? 'success' : 'error', $result->message);
    }
}
