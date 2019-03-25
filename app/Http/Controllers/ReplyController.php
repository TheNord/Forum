<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\Reply\CreateRequest;
use App\Http\Services\ReplyService;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    private $service;

    public function __construct(ReplyService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    public function store(CreateRequest $request, Channel $channel, Thread $thread)
    {
        $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return back()->with('flash', 'Your reply has been left.');
    }

    public function edit(Reply $reply)
    {
        //
    }

    public function update(Request $request, Reply $reply)
    {
        try {
            $this->isOwner($reply);

            $request->validate([
                'body' => 'required',
            ]);

            $reply->update([
                'body' => $request->body,
            ]);

            return response('Reply successfully updated', 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 403);
        }
    }

    public function destroy(Channel $channel, Thread $thread, Reply $reply)
    {
        try {
            $this->service->deleteReply($reply);
            return back()->with('flash', 'Your reply has been deleted.');
        } catch (\Exception $e) {
            return back()->with('flash', $e->getMessage());
        }
    }

    public function isOwner(Reply $reply)
    {
        if ($reply->user_id != auth()->id()) {
            throw new \Exception('You can not perform this action');
        }
    }
}
