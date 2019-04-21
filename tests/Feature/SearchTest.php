<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);

        create('App\Thread');
        create('App\Thread', ['body' => 'A thread with foobar']);
        create('App\Thread', ['body' => 'One more thread with foobar']);

        do {
            $results = $this->getJson('/threads/search?q=foobar')->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        Thread::latest()->take(3)->unsearchable();
    }
}