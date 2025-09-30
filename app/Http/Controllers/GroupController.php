<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

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
        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
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
        return redirect()->route('groups.index')->with('success', 'Group deleted successfuly');
    }
}
