<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Reply;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test  */

    function guests_can_not_favorite_anything(){

        $this->post('replies/1/favorites')
            ->assertRedirect('/login');

    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->singIn();

        $reply = create(Reply::class);

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);

    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->withoutExceptionHandling();

        $this->singIn();

        $reply = create(Reply::class);

        $reply->favorite();

        $this->delete('replies/' . $reply->id . '/favorites');

        $this->assertCount(0, $reply->favorites);

    }

    /** @test  */
    public function an_authenticated_user_may_only_favorite_a_reply_once(){

        $this->singIn();

        $reply = create(Reply::class);

        try {

            $this->post('replies/' . $reply->id . '/favorites');

            $this->post('replies/' . $reply->id . '/favorites');

        } catch (Exception $e){

            $this->fail('Did not except to insert the same record set twice');

        }

        $this->assertCount(1, $reply->favorites);

    }
}
