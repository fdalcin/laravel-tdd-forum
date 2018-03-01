<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_unauthenticated_user_may_not_add_replies()
    {
        $this->post('/threads/channel/1/replies')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_may_add_replies()
    {
        $thread = create('App\Thread');

        $reply = make('App\Reply');

        $this->signIn()
            ->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
