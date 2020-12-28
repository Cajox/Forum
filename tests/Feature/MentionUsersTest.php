<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function mentioned_users_in_a_reply_are_notified(){

        $john = create(User::class, ['name' => 'JohnDoe']);
        $this->singIn($john);

        $jane = create(User::class, ['name' => 'JaneDoe']);
        $this->singIn($jane);

        $thread = create(Thread::class);

        $reply = make(Reply::class,[
            'body' => '@JaneDoe look at this.'
        ]);

        $this->json('post',$thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);

    }

    /** @test */

    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters(){

        create(User::class, ['name' => 'JohnDoe1']);
        create(User::class, ['name' => 'JohnDoe2']);

        $result = $this->json('GET', 'api/users', ['name' => 'John']);
//dd($result);
        $this->assertCount(2, $result->json());


    }
}
