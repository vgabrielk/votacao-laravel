<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Auth;

// Rotas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/auth/google', [GoogleController::class, 'handleGoogleAuth']);
});

// Rotas protegidas
Route::middleware(['auth', 'user.status'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $user->load(['friends', 'friendRequests']);
        return view('dashboard', compact('user'));
    })->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/friends', [FriendsController::class, 'index'])->name('friends.index');
    Route::get('/friends/add', [FriendsController::class, 'create'])->name('friends.create');
    Route::post('/friends/invite', [FriendsController::class, 'addFriend'])->name('friends.store');
    Route::delete('/friends/{pivot}/reject', [FriendsController::class, 'rejectFriend'])->name('friends.removeFriend');
    Route::delete('/friends/{pivot}/delete', [FriendsController::class, 'deleteFriend'])->name('friends.deleteFriend');
    Route::post('/friends/{pivot}/accept', [FriendsController::class, 'acceptFriend'])->name('friends.acceptFriend');

    // Rotas de Enquetes
    Route::get('/polls', [PollController::class, 'index'])->name('polls.index');
    Route::get('/polls/create', [PollController::class, 'create'])->name('polls.create');
    Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
    Route::get('/polls/{poll}', [PollController::class, 'show'])->name('polls.show');
    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');
    Route::post('/polls/{poll}/publish', [PollController::class, 'publish'])->name('polls.publish');
    Route::post('/polls/{poll}/close', [PollController::class, 'close'])->name('polls.close');
    Route::delete('/polls/{poll}', [PollController::class, 'destroy'])->name('polls.destroy');

});
