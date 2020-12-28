<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ThreadsTest extends TestCase
{

    use RefreshDatabase;
/*    use DatabaseTransactions;*/

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory('App\Models\Thread')->create();

    }

    /** @test */
    public function a_user_can_view_all_threads()
    {

/*        $thread = factory('App\Models\Thread')->create();*/

        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);


    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {

/*        $thread = factory('App\Models\Thread')->create();*/

        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);

    }

    /** @test */
/*    public function a_user_can_read_replies_that_are_associated_with_a_thread(){

        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())->assertSee($reply->body);

    }*/

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel(){

        $this->withoutExceptionHandling();

        $channel = create(Channel::class);

        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

/*        dump($channel->id . ' ');
        dd($threadInChannel->id . '  ' . $threadNotInChannel->id);*/


        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);

    }

    /** @test */

    function a_user_can_filter_threads_by_any_username(){

//        $this->withoutExceptionHandling();

        $this->singIn(create(User::class, ['name'=>'JohnDoe']));

        $threadByJohn = create(Thread::class, ['user_id' => Auth::id()]);
        $threadNotByJohn = create(Thread::class);

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);

    }

    /** @test */

    function a_user_can_filter_threads_by_popularity(){

        $this->withoutExceptionHandling();

        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));

    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered(){

        $thread = create(Thread::class);

        create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);

    }
}
