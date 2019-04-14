<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\Thread\CreateRequest;
use App\Http\Services\ThreadService;
use App\Service\ThreadVisitsService;
use App\Service\TrendingService;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    private $service;

    public function __construct(ThreadService $service)
    {
        $this->middleware('auth')->except('index', 'show');
        $this->service = $service;
    }

    public function index(Request $request, TrendingService $trending)
    {

        $threads = $this->service->filterThread($request)->paginate(5);

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    public function create()
    {
        $channels = Channel::all();
        return view('threads.create', compact('channels'));
    }

    public function store(CreateRequest $request)
    {
        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => $request->channel_id,
            'title' => $request->title,
            'body' => $request->body,
            'slug' => str_slug($request->title),
        ]);

        return redirect()->route('threads.show', [$thread->channel, $thread])
            ->with('flash', 'Your thread has been published');
    }

    public function show(Channel $channel, Thread $thread, TrendingService $trending, ThreadVisitsService $threadVisits)
    {
        $thread->updateThreadUnViewedCache();

        $trending->increment($thread);
        $threadVisits->recordVisit($thread);

        return view('threads.show', compact('thread'));
    }

    public function destroy(Channel $channel, Thread $thread)
    {
        try {
            $this->service->deleteThread($thread);
            return redirect()->route('threads.index')
                ->with('flash', 'The thread successfully deleted');
        } catch (\Exception $e) {
            return back()->with('flash', $e->getMessage());
        }

    }
}
