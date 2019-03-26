<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guest_can_not_favorite_anything()
    {
        $reply = create('App\Reply');

        $this->post(route('reply.favorite', $reply->id))
            ->assertRedirect(route('login'));
    }

    /**
    * @test
    */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn(create('App\User'));

        $reply = create('App\Reply');

        $res = $this->post(route('reply.favorite', $reply->id));

        $this->assertCount(1, $reply->favorites);
    }

    /**
    * @test
    */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn(create('App\User'));

        $reply = create('App\Reply');

        $res = $this->post(route('reply.favorite', $reply->id));

        $this->assertCount(1, $reply->favorites);

        $res = $this->delete(route('reply.favorite', $reply->id));

        $this->assertCount(0, $reply->fresh()->favorites);
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {

        $this->signIn(create('App\User'));

        $reply = create('App\Reply');

        $this->post(route('reply.favorite', $reply->id));
        $this->post(route('reply.favorite', $reply->id));

        $this->assertCount(1, $reply->favorites);
    }

    /**
     * @test
     */
    public function an_user_can_not_favorite_owner_reply()
    {
        $user = create('App\User');

        $this->signIn($user);

        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->post(route('reply.favorite', $reply->id));

        $this->assertCount(0, $reply->favorites);
    }
}
