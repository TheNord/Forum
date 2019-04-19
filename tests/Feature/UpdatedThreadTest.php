<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UpdatedThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_thread_can_be_updated()
    {
        $this->signIn($user = create('App\User'));

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->json('put', $thread->path(), ['body' => 'Updated body']);

        $this->assertEquals('Updated body', $thread->fresh()->body);
    }

    /**
     * @test
     */
    public function thread_with_replies_can_not_be_updated()
    {
        $this->signIn($user = create('App\User'));

        $thread = create('App\Thread', ['user_id' => $user->id]);

        create('App\Reply', ['thread_id' => $thread->id]);

        $this->json('put', $thread->path(), ['body' => 'Updated body'])
            ->assertStatus(403)
            ->assertSee('Thread with replies can not be updated');

        $this->assertEquals($thread->body, $thread->fresh()->body);
    }

    /**
     * @test
     */
    public function locked_thread_can_not_be_updated()
    {
        $this->signIn($user = create('App\User'));

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $thread->lock();

        $this->json('put', $thread->path(), ['body' => 'Updated body'])
            ->assertStatus(403)
            ->assertSee('Locked thread can not be updated');

        $this->assertEquals($thread->body, $thread->fresh()->body);
    }

    /**
    * @test
    */
    public function a_thread_can_be_updated_only_owner()
    {
        $this->signIn(create('App\User'));

        $thread = create('App\Thread');

        $this->json('put', $thread->path(), ['body' => 'Updated body'])
            ->assertSee('You can not edit this thread')
            ->assertStatus(403);

        $this->assertEquals($thread->body, $thread->fresh()->body);
    }
}
