<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\Reply\CreateRequest;
use App\Http\Resources\ReplyResource;
use App\Http\Services\ReplyService;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    private $service;

    public function __construct(ReplyService $service)
    {
        $this->middleware('auth')->except('getReplies');
        $this->service = $service;
    }

    public function store(CreateRequest $request, Channel $channel, Thread $thread)
    {
        $reply = $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return response(new ReplyResource($reply), 200);
    }

    public function edit(Reply $reply)
    {
        //
    }

    public function getReplies(Thread $thread)
    {
        $replies = ReplyResource::collection($thread->replies()->get());
        return response($replies, 200);
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
            return response('Your reply has been deleted.', 201);
        } catch (\Exception $e) {
            return response($e->getMessage(), 403);
        }
    }

    public function isOwner(Reply $reply)
    {
        if ($reply->user_id != auth()->id()) {
            throw new \Exception('You can not perform this action');
        }
    }
}
