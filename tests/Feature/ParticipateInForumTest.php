<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_unauthenticated_user_may_not_add_replies()
    {
        $this->withoutExceptionHandling();

        $this->expectException(\Illuminate\Auth\AuthenticationException::class);

        $this->post('/threads/1/replies', []);
    }

    /** @test */
    function an_authenticated_user_may_add_replies()
    {
        $this->withoutExceptionHandling();

        $thread = factory(\App\Thread::class)->create();

        $reply = factory(\App\Reply::class)->make();

        $this->actingAs(factory(\App\User::class)->create())
            ->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
