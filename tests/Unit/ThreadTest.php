<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

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
    function it_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => create('App\User')->id,
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
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

    /** @test */
    function it_knows_if_authenticated_user_is_subscribed_to()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedTo);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribedTo);
    }

    /** @test */
    function it_can_check_if_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        tap(auth()->user(), function ($user) {
            $this->assertTrue($this->thread->hasUpdatesFor($user));

            $user->read($this->thread);

            $this->assertFalse($this->thread->hasUpdatesFor($user));
        });
    }
}
