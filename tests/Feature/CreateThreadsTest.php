<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_create_new_threads()
    {
        $thread = make('App\Thread');

        $response = $this->signIn()
            ->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    function it_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function it_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function it_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function guests_can_not_delete_threads()
    {
        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');
    }

    /** @test */
    function it_can_be_deleted()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path());

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    function threads_may_only_be_deleted_by_those_who_have_permission()
    {
        //TODO:
    }

    protected function publishThread($attributes = [])
    {
        $this->signIn();

        $thread = make('App\Thread', $attributes);

        return $this->post('/threads', $thread->toArray());
    }
}
