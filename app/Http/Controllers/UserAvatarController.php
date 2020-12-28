<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAvatarController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(){

        $this->validate(\request(),[

            'avatar' => ['required', 'image']

        ]);

        Auth::user()->update([

            'avatar_path' => \request()->file('avatar')->store('avatars', 'public')

        ]);

        return back();

    }

}
