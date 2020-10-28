<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActivityTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_records_activity_when_a_thread_is_created(){

        $this->singIn();

        $thread = create(Thread::class);

        $this->assertDatabaseHas('activities', [

            'type' => 'created_thread',
            'user_id' => Auth::id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Models\Thread'

        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);

    }

    /** @test */
    function it_records_activity_when_a_reply_is_created(){

        $this->singIn();

        $reply = create(Reply::class);

        $this->assertEquals(2, Activity::count());

    }

    /** @test */
    function it_fetches_a_feed_for_any_user(){

        $this->singIn();

        create(Thread::class, ['user_id' => Auth::id()], 2);

        Auth::user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(Auth::user(), 50);

        $this->assertTrue($feed->keys()->contains(

            Carbon::now()->format('Y-m-d')

        ));

        $this->assertTrue($feed->keys()->contains(

            Carbon::now()->subWeek()->format('Y-m-d')

        ));


    }

}
