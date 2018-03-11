<?php

namespace Tests\Feature;

use App\Activity;
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

        $this->post(route('threads.index'))
            ->assertRedirect('/login');
    }

    /** @test */
    function new_users_must_confirm_their_email_address_before_creating_threads()
    {
        $this->signIn(
            factory('App\User')->states('unconfirmed')->create()
        );

        $thread = make('App\Thread');

        $this->post(route('threads.index'), $thread->toArray())
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash');
    }

    /** @test */
    function a_user_can_create_new_threads()
    {
        $thread = make('App\Thread');

        $response = $this->signIn()
            ->post(route('threads.index'), $thread->toArray());

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
        create('App\Channel', [], 2);

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function it_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Foo Bar Title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-bar-title');

        $thread = $this->postJson(route('threads.store'), $thread->toArray())->json();

        $this->assertEquals("foo-bar-title-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    function a_thread_with_a_title_that_ends_with_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Foo Bar 24']);

        $this->assertEquals($thread->fresh()->slug, 'foo-bar-24');

        $thread = $this->postJson(route('threads.store'), $thread->toArray())->json();

        $this->assertEquals("foo-bar-24-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    function unauthorized_users_may_not_delete_threads()
    {
        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path());

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }

    protected function publishThread($attributes = [])
    {
        $this->signIn();

        $thread = make('App\Thread', $attributes);

        return $this->post(route('threads.index'), $thread->toArray());
    }
}
