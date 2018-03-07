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

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->signIn()
            ->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $reply = create('App\Reply');

        $this->delete('/replies/' . $reply->id)
            ->assertRedirect('/login');

        $this->signIn()
            ->delete('/replies/' . $reply->id)
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete('/replies/' . $reply->id)->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $reply = create('App\Reply');

        $this->patch('/replies/' . $reply->id)
            ->assertRedirect('/login');

        $this->signIn()
            ->patch('/replies/' . $reply->id)
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = "You've been changed, fool.";

        $this->patch('/replies/' . $reply->id, ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }
}
