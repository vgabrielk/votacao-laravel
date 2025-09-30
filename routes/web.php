<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\GroupController;

use App\Http\Controllers\PollController;

use Illuminate\Support\Facades\Auth;

// Rotas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rotas protegidas
Route::middleware(['auth', 'user.status'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $user->load(['friends', 'friendRequests', 'groups']);
        return view('dashboard', compact('user'));
    })->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');


    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::post('/groups/{group}/addMember', [GroupController::class, 'addMember'])->name('groups.addMember');

    Route::get('/friends', [FriendsController::class, 'index'])->name('friends.index');
    Route::get('/friends/add', [FriendsController::class, 'create'])->name('friends.create');
    Route::post('/friends/invite', [FriendsController::class, 'addFriend'])->name('friends.store');
    Route::delete('/friends/{pivot}/reject', [FriendsController::class, 'rejectFriend'])->name('friends.removeFriend');
    Route::post('/friends/{pivot}/accept', [FriendsController::class, 'acceptFriend'])->name('friends.acceptFriend');


    // Rotas de Enquetes (apenas dentro de grupos)
    Route::get('/groups/{group}/polls', [PollController::class, 'index'])->name('polls.index');
    Route::get('/groups/{group}/polls/create', [PollController::class, 'create'])->name('polls.create');
    Route::post('/groups/{group}/polls', [PollController::class, 'store'])->name('polls.store');
    Route::get('/groups/{group}/polls/{poll}', [PollController::class, 'show'])->name('polls.show');
    Route::post('/groups/{group}/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');
    Route::post('/groups/{group}/polls/{poll}/publish', [PollController::class, 'publish'])->name('polls.publish');
    Route::post('/groups/{group}/polls/{poll}/close', [PollController::class, 'close'])->name('polls.close');
    Route::delete('/groups/{group}/polls/{poll}', [PollController::class, 'destroy'])->name('polls.destroy');
});
