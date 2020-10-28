<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Exception;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

/*    use WithoutMiddleware;*/

    /** @test */

    function unauthenticated_users_may_not_add_replies(){

        $this->withExceptionHandling()
            ->post('/threads/some_channel/1/replies', [])
            ->assertRedirect('/login');

    }

    /** @test */

    function an_authenticated_user_may_participate_in_forum_threads(){

/*        $this->withoutExceptionHandling();*/

        $user = factory(User::class)->create();
        $this->be($user);
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);


    }

    /** @test */

    function a_reply_requires_a_body(){

        $this->singIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
/*            ->assertSessionHasErrors('body');*/
              ->assertStatus(422);

    }

    /** @test */
    function unauthorized_users_cannot_delete_replies(){

        $reply = create(Reply::class);

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->singIn()->delete("/replies/{$reply->id}")
            ->assertStatus(403);

    }

    /** @test */
    function authorized_users_can_delete_replies(){

        $this->singIn();

        $reply = create(Reply::class, ['user_id' => Auth::id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);

    }

    /** @test */
    function authorized_users_can_update_replies(){

        $this->singIn();

        $reply = create(Reply::class, ['user_id' => Auth::id()]);

        $this->patch("/replies/{$reply->id}",['body' => 'You been changed, fool']);

        $this->assertDatabaseHas('replies', ['id' =>$reply->id, 'body'=>'You been changed, fool']);

    }

    /** @test */
    function unauthorized_users_cannot_update_replies(){

        $reply = create(Reply::class);

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->singIn()->delete("/replies/{$reply->id}")
            ->assertStatus(403);

    }

    /** @test */

    function replies_that_contain_spam_may_not_be_created(){

/*        $this->withoutExceptionHandling();*/

        $this->singIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, [

            'body' => 'yahoo customer support'

        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(422);

    }

}
