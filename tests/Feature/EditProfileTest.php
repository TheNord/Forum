<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditProfileTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
    * @test
    */
    public function only_members_can_edit_profile()
    {
        $this->json('GET', route('user.profile.edit'))
            ->assertStatus(401);
    }

    /**
    * @test
    */
    public function user_can_change_name_on_only_unique()
    {
        create('App\User', ['name' => 'UserName']);

        $this->signIn($userOne = create('App\User'));

        $this->json('PUT', route('user.profile.edit'), [
            'name' => 'UserName'
        ])->assertStatus(422);
    }

    /**
    * @test
    */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn(create('App\User'));

        $this->json('PUT', route('user.profile.edit'), [
            'name' => 'UserName',
            'avatar' => 'non-valid-avatar'
        ])->assertStatus(422);
    }
    
    /**
    * @test
    */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn(create('App\User'));

        Storage::fake('public');

        $this->json('PUT', route('user.profile.edit'), [
            'name' => 'UserName',
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        Storage::disk('public')->assertExists("avatars/{$file->hashName()}");

        $this->assertEquals("avatars/{$file->hashName()}", auth()->user()->avatar_path);
    }
}
