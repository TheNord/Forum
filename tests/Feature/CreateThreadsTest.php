<?php

namespace Tests\Feature;

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
    public function an_authenticated_user_can_create_new_threads()
    {
        $this->signIn(create('App\User'));

        $thread = make('App\Thread');

        $this->post(route('threads.store'), $thread->toArray());

        $this->get(route('threads.show', $thread))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
