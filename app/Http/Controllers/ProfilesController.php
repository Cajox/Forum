<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
/*    use RefreshDatabase;*/

    public function show(User $user){

        return view('profiles.show', [

            'profileUser' => $user,
            'activities' => Activity::feed($user)

        ]);

    }


}
