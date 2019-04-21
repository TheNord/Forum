<?php

namespace Tests\Unit;

use App\Service\ThreadVisitsService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
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
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }
    
    /**
    * @test
    */
    public function a_thread_has_a_unique_slug()
    {
        $thread = create('App\Thread');

        $slug = str_slug($thread->title);

        $this->assertEquals($slug, $thread->slug);
    }
    
    /**
    * @test
    */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }
    
    /**
    * @test
    */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
    
    /**
    * @test
    */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
    
    /**
    * @test
    */
    public function a_thread_can_be_subscribed_to()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');
        $thread->subscribe($user);

        $subscriptions = $thread->subscriptions()->where('user_id', $user->id)->count();

        $this->assertEquals(1, $subscriptions);
    }
    
    /**
    * @test
    */
    public function a_thread_can_be_unsubscribed_from()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');
        $thread->unsubscribe($user);

        $subscriptions = $thread->subscriptions()->where('user_id', $user->id)->count();

        $this->assertEquals(0, $subscriptions);
    }
    
    /**
    * @test
    */
    public function a_thread_record_each_visit()
    {
        $service = new ThreadVisitsService();

        $thread = make('App\Thread', ['id' => 1]);

        $service->resetVisits($thread);

        $service->recordVisit($thread);

        $this->assertEquals(1, $service->visits($thread));

        $service->recordVisit($thread);

        $this->assertEquals(2, $service->visits($thread));
    }

    /** @test */
    function a_threads_body_is_sanitized_automatically()
    {
        $thread = make('App\Thread', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);
        $this->assertEquals("<p>This is okay.</p>", $thread->body);
    }
}
