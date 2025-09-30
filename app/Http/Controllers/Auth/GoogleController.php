<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class GoogleController extends Controller
{
    public function handleGoogleAuth(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'photo' => 'nullable|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            Auth::login($user);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(uniqid()),
                'google_uid' => $request->uid,
                'avatar' => $request->photo,
                'email_verified_at' => now(),
            ]);

            Mail::to($request->email)->send(new WelcomeMail($user));

            Auth::login($user);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user
        ]);
    }
}
