<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct(
        private GroupService $groupService
    ) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $groups = $this->groupService->getAllGroupsWithMembers($userId);
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

        $result = $this->groupService->createNewGroup($user, $request->validated());

        return redirect()->route('groups.index')
        ->with($result->success ? 'success' : 'error', $result->message);

    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {

$group->load(['polls.creator', 'polls.options']);

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
        $result = $this->groupService->destroyGroup($group);
        return redirect()->route('groups.index')
            ->with($result->success ? 'success' : 'error', $result->message);
    }


    public function addMember(Request $request, Group $group)
    {

        $this->authorize('canAddOtherUsers', $group);

        $result = $this->groupService->addMember($request->email, $group);

        return redirect()->route('groups.index')
            ->with($result->success ? 'success' : 'error', $result->message);

    }

}
