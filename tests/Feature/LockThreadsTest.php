<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_administrator_can_lock_any_thread()
    {
        $thread = create('App\Thread');

        $thread->lock();

        $this->signIn();

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar'
        ])->assertStatus(422);
    }
}