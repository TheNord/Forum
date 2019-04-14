<?php

namespace Tests\Feature;

use App\Reply;
use App\Service\TrendingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->signIn($user = create('App\User'));

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $replyOne = create('App\Reply', ['thread_id' => $thread->id]);
        $replyTwo = create('App\Reply', ['thread_id' => $thread->id]);

        $this->json('POST', route('reply.best.mark', $replyOne->id));

        $this->assertTrue($replyOne->fresh()->isBest());
        $this->assertFalse($replyTwo->fresh()->isBest());
    }

    /**
     * @test
     */
    public function a_thread_creator_may_un_mark_any_reply_as_the_best_reply()
    {
        $this->signIn($user = create('App\User'));

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->json('POST', route('reply.best.mark', $reply->id));

        $this->assertTrue($reply->fresh()->isBest());

        $this->json('DELETE', route('reply.best.unMark', $reply->id));

        $this->assertFalse($reply->fresh()->isBest());
    }
    
    /**
    * @test
    */
    public function only_the_thread_creator_may_mark_a_reply_is_best()
    {
        $thread = create('App\Thread');

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->signIn(create('App\User'));

        $this->json('POST', route('reply.best.mark', $reply->id))
            ->assertSee('You can not mark this reply as best.');

    }

    /**
    * @test
    */
    public function author_can_not_mark_as_best_their_reply()
    {
        $this->signIn($user = create('App\User'));

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $reply = create('App\Reply', ['thread_id' => $thread->id, 'user_id' => $user->id]);

        $this->json('POST', route('reply.best.mark', $reply->id))
            ->assertSee('You can not mark this reply as best.');
    }
}
