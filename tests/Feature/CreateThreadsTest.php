<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test  */

    function guests_may_not_create_threads(){

/*        $this->withoutExceptionHandling();*/

/*        $this->singIn();*/

        $this->post('threadds/store')->assertRedirect('/login');
/*        $this->get('threads/create')->assertRedirect('/login');*/

    }


    /** @test */

    function an_authenticated_user_can_create_new_forum_threads(){

/*        $this->withoutExceptionHandling();*/

        $this->singIn();

        $thread = make(Thread::class);

        $response = $this->post('/threadds/store', $thread->toArray());

        $url = $response->headers->get('Location');

        $link_array = explode('/',$url);

        $slug = $link_array[4];
        $id = end($link_array);

        $this->get('/threads/' . $slug . '/' . $id)->assertSee($thread->title);

    }

    /** @test */

    function a_thread_requires_a_title(){

        $this->publishThread(['title'=> null])
            ->assertSessionHasErrors('title');

    }

    /** @test */

    function a_thread_requires_a_body(){

        $this->publishThread(['body'=> null])
            ->assertSessionHasErrors('body');

    }

    /** @test */

    function a_thread_requires_a_valid_channel(){

        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id'=> null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id'=> 9999])
            ->assertSessionHasErrors('channel_id');

    }

    /** @test */
    function unauthorized_users_may_not_delete_threads(){

        $thread = create(Thread::class);

        $response = $this->delete( $thread->path());
//      $response = $this->json('DELETE', $thread->path());
        $response->assertRedirect('/login');

        $this->singIn();

        $response = $this->delete( $thread->path());
        $response->assertStatus(403);


    }


    /** @test */
    function authorized_users_can_deleted_threads(){

        $this->singIn();

        $thread = create(Thread::class, ['user_id'=> Auth::id()]);
        $reply = create(Reply::class, ['thread_id'=>$thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id'=>$thread->id]);
        $this->assertDatabaseMissing('replies', ['id'=>$reply->id]);
        $this->assertDatabaseMissing('activities', [

            'subject_id'=>$thread->id,
            'subject_type'=>get_class($thread)

        ]);

        $this->assertDatabaseMissing('activities', [

            'subject_id'=>$reply->id,
            'subject_type'=>get_class($reply)

        ]);

        $this->assertEquals(0, Activity::count());

    }

    protected function publishThread($overrides = []){

        $this->singIn();

        $thread = make(Thread::class, $overrides);

        return $this->post('/threadds/store', $thread->toArray());

    }

}
