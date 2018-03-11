<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'johndoe@example.com',
            'password'              => 'secret',
            'password_confirmation' => 'secret',
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    function user_can_fully_confirm_their_email_address()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'johndoe@example.com',
            'password'              => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(
            route('register.confirm', ['token' => $user->confirmation_token])
        )->assertRedirect(route('threads.index'));

        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });

    }

    /** @test */
    function confirming_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash');
    }
}