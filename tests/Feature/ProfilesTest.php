<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    function a_user_has_a_profile(){

/*        $this->withoutExceptionHandling();*/

        $user = create(User::class);

        $this->get('/profiles/' . $user->name)
            ->assertSee($user->name);

    }

    /** @test */
    function profiles_display_all_threads_created_by_the_associated_user(){

        $this->singIn();

        $thread = create(Thread::class, ['user_id'=> Auth::id()]);

        $this->get('/profiles/' . Auth::user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);

    }

}
