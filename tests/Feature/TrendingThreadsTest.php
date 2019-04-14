<?php

namespace Tests\Feature;

use App\Service\TrendingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $trending;

    protected function setUp()
    {
        parent::setUp();

        $this->trending = new TrendingService();
        $this->trending->clear();
    }

    /**
    * @test
    */
    public function it_increment_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = create('App\Thread');

        $this->json('GET', $thread->path());

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
