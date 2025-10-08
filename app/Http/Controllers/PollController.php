<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $result = $this->pollService->listPolls();

        if (!$result->success) {
            return redirect()->back()
                ->with('error', $result->message);
        }

        $polls = $result->data['polls'] ?? collect();
        return view('polls.index', compact('result', 'polls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('polls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->pollService->createPoll($request);

        if (!$result->success) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result->message);
        }

        return redirect()->route('polls.index')
            ->with('success', $result->message);
    }

    /**
     * Display the specified resource.
     */
    public function show($pollId)
    {
        $result = $this->pollService->getPoll($pollId);

        if (!$result->success) {
            return redirect()->route('polls.index')
                ->with('error', $result->message);
        }

        $poll = $result->data['poll'];
        return view('polls.show', compact('poll'));
    }

    /**
     * Publish a draft poll.
     */
    public function publish($pollId)
    {
        $result = $this->pollService->publishPoll($pollId);

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
    public function close($pollId)
    {
        $result = $this->pollService->closePoll($pollId);

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
    public function vote(Request $request, $pollId)
    {
        $request->validate([
            'option_id' => 'required|exists:poll_options,id'
        ]);

        $result = $this->pollService->votePoll($pollId, $request->option_id, Auth::id());

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
    public function destroy($pollId)
    {
        $result = $this->pollService->deletePoll($pollId);

        if (!$result->success) {
            return redirect()->back()
                ->with('error', $result->message);
        }

        return redirect()->route('polls.index')
            ->with('success', $result->message);
    }
}