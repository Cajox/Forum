<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    function only_members_can_add_avatars(){

        $this->json('POST','api/users/1/avatar')->assertStatus(401);

    }

    /** @test */
    function a_valid_avatar_must_be_provided(){

        $this->singIn();

        $this->json('POST','api/users/' . Auth::id() . '/avatar', [

            'avatar' => 'not_an_image'

        ])->assertStatus(422);

    }

    /** @test */
    function a_user_may_add_an_avatar_to_their_profile(){

        $this->singIn();

        Storage::fake('public');

        $this->json('POST','api/users/' . Auth::id() . '/avatar', [

            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')

        ]);

        $this->assertEquals('avatars/' . $file->hashName(), Auth::user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());

    }

}
