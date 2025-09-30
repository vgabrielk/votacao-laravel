<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\PollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function __construct(
        private PollService $pollService
    ) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group)
    {
        $result = $this->pollService->listPolls($group);

        if (!$result->success) {
            return redirect()->back()
                ->with('error', $result->message);
        }

        $polls = $result->data['polls'] ?? collect();
        return view('polls.index', compact('group', 'result', 'polls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Group $group)
    {
        return view('polls.create', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Group $group)
    {
        $result = $this->pollService->createPoll($group, $request);

        if (!$result->success) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result->message);
        }

        return redirect()->route('polls.index', $group)
            ->with('success', $result->message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, $pollId)
    {
        $result = $this->pollService->getPoll($group, $pollId);

        if (!$result->success) {
            return redirect()->route('polls.index', $group)
                ->with('error', $result->message);
        }

        $poll = $result->data['poll'];
        return view('polls.show', compact('group', 'poll'));
    }

    /**
     * Publish a draft poll.
     */
    public function publish(Group $group, $pollId)
    {
        $result = $this->pollService->publishPoll($group, $pollId);

        if (!$result->success) {
            return redirect()->back()
                ->with('error', $result->message);
        }

        return redirect()->back()
            ->with('success', $result->message);
    }

    /**
     * Close an open poll.
     */
    public function close(Group $group, $pollId)
    {
        $result = $this->pollService->closePoll($group, $pollId);

        if (!$result->success) {
            return redirect()->back()
                ->with('error', $result->message);
        }

        return redirect()->back()
            ->with('success', $result->message);
    }

    /**
     * Vote on a poll.
     */
    public function vote(Request $request, Group $group, $pollId)
    {
        $request->validate([
            'option_id' => 'required|exists:poll_options,id'
        ]);

        $result = $this->pollService->votePoll($group, $pollId, $request->option_id, Auth::id());

        if (!$result->success) {
            return redirect()->back()
                ->with('error', $result->message);
        }

        return redirect()->back()
            ->with('success', $result->message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, $pollId)
    {
        // Por enquanto, apenas redireciona de volta
        return redirect()->route('polls.index', $group)->with('success', 'Enquete excluída com sucesso!');
    }
}
