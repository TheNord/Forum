<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\Reply\CreateRequest;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(CreateRequest $request, Channel $channel, Thread $thread)
    {
        $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('threads.show', [$thread->channel->slug, $thread->id])
            ->with('flash', 'Your reply has been left.');
    }

    public function edit(Reply $reply)
    {
        //
    }

    public function update(Request $request, Reply $reply)
    {
        //
    }

    public function destroy(Reply $reply)
    {
        //
    }
}
