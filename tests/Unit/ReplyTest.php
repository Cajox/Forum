<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReplyTest extends TestCase
{

    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->reply = factory('App\Models\Reply')->create();

    }

    /** @test */
    function it_has_an_owner(){

/*        $reply = factory('App\Models\Reply')->create();*/

        $this->assertInstanceOf(User::class, $this->reply->owner);

    }

    /** @test */
    function it_knows_it_is_was_just_published(){

        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());


    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body(){

        $reply = create(Reply::class, [

            'body' => '@JaneDoe wants to talk to @JohnDoe'

        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());

    }

}
