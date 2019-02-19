<?php

namespace Tests\Feature;

use App\Thread;
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
        $this->thread = create('App\Thread');
    }

    /**
     * @test
     */
    public function a_user_can_view_all_threads()
    {
        $this->get(route('threads.index'))
            ->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function a_user_can_view_single_thread()
    {
        $this->get(route('threads.show', [$this->thread->channel, $this->thread]))
            ->assertSee($this->thread->title);
    }

    /**
    * @test
    */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);

        $this->get(route('threads.show', [$this->thread->channel, $this->thread]))
            ->assertSee($reply->body);
    }
    
    /**
    * @test
    */
    public function a_user_can_filter_according_to_a_tag()
    {
        $this->signIn(create('App\User'));

        $channel = create('App\Channel');

        $thread = create('App\Thread', ['channel_id' => $channel->id]);
        $otherChannelThread = create('App\Thread');

        $this->get(route('channel.show', $thread->channel))
            ->assertSee($thread->body)
            ->assertDontSee($otherChannelThread->body);
    }
    
    /**
    * @test
    */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'John']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('/?by=John')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
    
    /**
    * @test
    */
    public function thread_can_be_deleted()
    {
        $user = create('App\User');

        $this->signIn($user);

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->json('delete', route('threads.delete', [$thread->channel, $thread->id]));

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

    }

    /**
     * @test
     */
    public function thread_can_be_deleted_only_owner()
    {
        $user = create('App\User');

        $this->signIn($user);

        $thread = create('App\Thread');

        $this->json('delete', route('threads.delete', [$thread->channel, $thread->id]));

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

    }

    /**
    * @test
    */
    public function thread_with_replies_can_not_be_deleted()
    {
        $user = create('App\User');

        $this->signIn($user);

        $thread = create('App\Thread', ['user_id' => $user->id]);

        create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete(route('threads.delete', [$thread->channel, $thread->id]));

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

    }
}
