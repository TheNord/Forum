<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guests_may_not_created_threads()
    {
        $thread = make('App\Thread');

        $response = $this->post(route('threads.store'), $thread->toArray());
        $this->assertEquals(302, $response->getStatusCode());
    }
    
    /**
    * @test
    */
    public function guest_cannot_see_the_create_threat_page()
    {
        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_create_new_threads()
    {
        $this->signIn(create('App\User'));

        $thread = create('App\Thread');

        $this->post(route('threads.store'), $thread->toArray());

        $this->get(route('threads.show', [$thread->channel, $thread->id]))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
    * @test
    */
    public function a_thread_require_a_title()
    {
        $this->signIn(factory('App\User')->state('confirmed')->create());

        $this->post(route('threads.store'))
            ->assertSessionHasErrors(['title']);
    }

    /**
    * @test
    */
    public function a_thread_require_a_body()
    {
        $this->signIn(factory('App\User')->state('confirmed')->create());

        $this->post(route('threads.store'))
            ->assertSessionHasErrors(['body']);
    }

    /**
    * @test
    */
    public function a_thread_require_a_channel()
    {
        $this->signIn(factory('App\User')->state('confirmed')->create());

        $this->post(route('threads.store'))
            ->assertSessionHasErrors(['channel_id']);
    }
    
    /**
    * @test
    */
    public function authenticated_users_must_first_confirm_their_email_before_creating_threads()
    {
        $this->signIn($user = create('App\User'));

        $thread = make('App\Thread');

        $this->json('POST', route('threads.store'), $thread->toArray())
            ->assertRedirect('/')
            ->assertSessionHas('flash', 'You must first confirm email address.');
    }
}
