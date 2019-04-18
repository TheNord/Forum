<?php

namespace Tests\Feature;

use App\Channel;
use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

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

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * @test
     */
    public function a_thread_require_a_unique_slug()
    {
        $this->signIn(factory('App\User')->state('confirmed')->create());

        $thread = create('App\Thread', ['title' => 'Thread Title', 'slug' => 'thread-title']);

        $this->assertEquals($thread->fresh()->slug, 'thread-title');

        $this->post(route('threads.store', $thread->toArray() + ['g-recaptcha-response' => 'token']));

        $this->assertTrue(Thread::whereSlug('thread-title-2')->exists());

        $this->post(route('threads.store', $thread->toArray() + ['g-recaptcha-response' => 'token']));

        $this->assertTrue(Thread::whereSlug('thread-title-3')->exists());
    }

    /**
     * @test
     */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn(factory('App\User')->state('confirmed')->create());

        $thread = create('App\Thread', ['title' => 'Thread Title 24', 'slug' => 'thread-title-24']);

        $this->post(route('threads.store', $thread->toArray() + ['g-recaptcha-response' => 'token']));

        $this->assertTrue(Thread::whereSlug('thread-title-24-2')->exists());
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
    public function a_thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->signIn(factory('App\User')->state('confirmed')->create());

        $this->post(route('threads.store'), ['g-recaptcha-response' => 'token'])
            ->assertSessionHasErrors(['g-recaptcha-response']);
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
