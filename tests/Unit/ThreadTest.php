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

        $this->thread = factory(\App\Thread::class)->create();
    }

    /** @test */
    function it_has_a_path()
    {
        $this->assertEquals('/threads/' . $this->thread->id, $this->thread->path());
    }

    /** @test */
    function it_has_replies()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $this->thread->replies);
    }

    /** @test */
    function it_has_an_owner()
    {
        $this->assertInstanceOf(\App\User::class, $this->thread->owner);
    }

    /** @test */
    function it_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
}
