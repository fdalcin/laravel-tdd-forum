<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    function it_checks_for_invalid_keywords()
    {
        $this->withoutExceptionHandling();

        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here.'));

        $this->expectException(\Exception::class);

        $spam->detect('Yahoo customer support.');
    }

    /** @test */
    function it_checks_for_any_key_being_held_down()
    {
        $this->withExceptionHandling();

        $spam = new Spam();

        $this->expectException(\Exception::class);

        $spam->detect('Hello world aaaaa');
    }
}
