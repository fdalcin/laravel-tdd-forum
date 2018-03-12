<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthorized_users_may_not_update_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test */
    function a_thread_requried_a_title_and_body_to_be_updated()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed Title',
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed body.',
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_can_be_updated_by_its_owner()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed Title',
            'body' => 'Changed body.'
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Changed Title', $thread->title);
            $this->assertEquals('Changed body.', $thread->body);
        });
    }
}
