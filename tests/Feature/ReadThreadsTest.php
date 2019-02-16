<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    private $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /**
     * @test
     */
    public function a_user_can_view_all_threads()
    {
        $this->get(route('threads'))
            ->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function a_user_can_view_single_thread()
    {
        $this->get(route('threads.show', $this->thread->id))
            ->assertSee($this->thread->title);
    }

    /**
    * @test
    */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory('App\Reply')
            ->create(['thread_id' => $this->thread->id]);

        $this->get(route('threads.show', $this->thread->id))
            ->assertSee($reply->body);
    }
}
