<?php

namespace Tests\Feature;

use App\Mail\ConfirmEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(ConfirmEmail::class);
    }
    
    /**
    * @test
    */
    public function user_can_fully_confirm_their_email()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'Jason',
            'email' => 'email@example.com',
            'password' => 'foobarfoo',
            'password_confirmation' => 'foobarfoo',
        ]);

        $user = User::whereName('Jason')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertSessionHas('flash', 'Your account has been confirmed.');

        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }
    
    /**
    * @test
    */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid-token']))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
