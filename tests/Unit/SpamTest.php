<?php

namespace Tests\Unit;

use App\Service\SpamDetection\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam();
        $this->expectException(\Exception::class);
        $this->assertFalse($spam->detect('Yahoo Customer support'));
    }
    
    /**
    * @test
    */
    public function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();
        $this->expectException(\Exception::class);
        $this->assertFalse($spam->detect('Hello world aaaaaaaaaaaaa'));
    }
}