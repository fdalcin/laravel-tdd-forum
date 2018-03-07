<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    function it_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    function it_can_make_a_string_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}",
            $this->thread->path()
        );
    }

    /** @test */
    function it_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    function it_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function it_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    function it_can_be_subscribed_to()
    {
        $this->signIn();

        $this->thread->subscribe();

        $this->assertEquals(1, $this->thread->subscriptions()->where('user_id', auth()->id())->count());

        $this->assertDatabaseHas('thread_subscriptions', ['user_id' => auth()->id(), 'thread_id' => $this->thread->id]);
    }

    /** @test */
    function it_can_be_unsubscribed_from()
    {
        $this->signIn();

        $this->thread->subscribe();

        $this->thread->unsubscribe();

        $this->assertCount(0, $this->thread->subscriptions);

        $this->assertDatabaseMissing('thread_subscriptions', ['user_id' => auth()->id(), 'thread_id' => $this->thread->id]);
    }
}
