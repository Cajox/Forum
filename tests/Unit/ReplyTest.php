<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
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
}
