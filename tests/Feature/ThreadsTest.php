<?php

namespace Tests\Feature;

use App\Service\ThreadVisitsService;
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

        $this->get("/thread/{$this->thread->id}/replies")
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
    public function a_user_can_filter_unaswered_threads()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);

        $this->get(route('threads.index') . '/?unanswered=1')
            ->assertSee($this->thread->title)
            ->assertDontSee($thread->title);
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
    public function a_thread_record_each_visit()
    {
        $thread = create('App\Thread');

        $visitService = new ThreadVisitsService();
        $visitService->resetVisits($thread);

        $this->json('GET', route('threads.show', [$thread->channel->name, $thread->id]))
            ->assertSee('Visits: 1');
    }
}
