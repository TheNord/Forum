<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_user_has_a_profile()
    {
        $user = create('App\User', ['name' => 'John Smith']);

        $this->get(route('user.profile', $user))
            ->assertSee($user->name);
    }

    /**
    * @test
    */
    public function a_user_has_a_created_threads_stats()
    {
        $user = create('App\User', ['name' => 'John Smith']);

        create('App\Thread', ['user_id' => $user->id]);

        $this->get(route('user.profile', $user))
            ->assertSee('1 thread');
    }

    /**
    * @test
    */
    public function a_user_has_a_reply_stats()
    {
        $user = create('App\User', ['name' => 'John Smith']);

        create('App\Reply', ['user_id' => $user->id]);

        $this->get(route('user.profile', $user))
            ->assertSee('1 replies');
    }

    /**
    * @test
    */
    public function a_user_has_a_receive_likes_stats()
    {
        $userOne = create('App\User', ['name' => 'John Smith']);
        $userTwo = create('App\User', ['name' => 'Paul Jordan']);

        $reply = create('App\Reply', ['user_id' => $userOne->id]);

        $this->signIn($userTwo);
        $this->post(route('reply.favorite', $reply->id));

        $this->get(route('user.profile', $userOne))
            ->assertSee('1 like');
    }
}
