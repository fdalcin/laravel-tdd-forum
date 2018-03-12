<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function non_administrators_may_not_lock_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    function administrators_can_lock_threads()
    {
        $this->signIn(
            factory('App\User')->states('administrator')->create()
        );

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    function administrators_can_unlock_threads()
    {
        $this->signIn(
            factory('App\User')->states('administrator')->create()
        );

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    function once_locked_a_thread_may_not_receive_new_replies()
    {
        $thread = create('App\Thread', ['locked' => true]);

        $this->signIn();

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar'
        ])->assertStatus(422);
    }
}