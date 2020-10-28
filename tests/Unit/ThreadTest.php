<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;


    protected $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();

    }

    /** @test  */

    function a_thread_can_make_a_string_path(){

        $thread = create(Thread::class);

        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());

    }

    /** @test */

    function a_thread_has_a_creator(){

        $this->assertInstanceOf(User::class, $this->thread->creator);

    }

    /** @test */

    function a_thread_has_replies(){

        $this->assertInstanceOf(Collection::class, $this->thread->replies);

    }

    /** @test */

    function a_thread_can_add_a_reply(){


        $this->thread->addReply([

            'body' => 'Foobar',
            'user_id'=> 1

        ]);

        $this->assertCount(1, $this->thread->replies);

    }

    /** @test */

    function  a_thread_notifies_all_registered_subscribers_when_a_reply_is_added(){

        Notification::fake();

        $this->singIn()
            ->thread
            ->subscribe()
            ->addReply([

            'body' => 'Foobar',
            'user_id'=> 9999

        ]);

        Notification::assertSentTo(Auth::user(), ThreadWasUpdated::class);

    }

    /** @test */

    function a_thread_belongs_to_a_channel(){

/*        $this->withoutExceptionHandling();*/

        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);

    }

    /** @test */

    function  a_thread_can_be_subscribed_to(){

/*        $this->withoutExceptionHandling();*/

        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());

    }

    /** @test */

    function a_thread_can_be_unsubscribed_from(){

        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0,$thread->subscriptions);

    }

    /** @test */

    function it_knows_if_the_authenticated_user_is_subscribed_to_it(){

        $thread = create(Thread::class);

        $this->singIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);

    }

    /** @test */

    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies(){

        $this->singIn();

        $thread = create(Thread::class);

        tap(Auth::user(), function ($user) use ($thread){

            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));

        });

    }


}
