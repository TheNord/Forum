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
        try {
            return $this->service->store($request, $thread);
        } catch (\Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function getReplies(Thread $thread)
    {
        $data = $thread->replies()->paginate(5);
        $replies = ReplyResource::collection($data);

        return response([
            'replies' => $replies,
            'paginate' => $data,
        ], 200);
    }

    public function update(Request $request, Reply $reply)
    {
        try {
            $this->isOwner($reply);
        } catch (\Exception $e) {
            return response($e->getMessage(), 403);
        }

        $request->validate([
            'body' => 'required|spamfree',
        ]);

        $reply->update([
            'body' => $request->body,
        ]);

        return response('Reply successfully updated', 200);

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

    public function markAsBest(Reply $reply)
    {
        if ($reply->thread->user_id != auth()->id() || $reply->user_id == auth()->id()) {
            return response('You can not mark this reply as best.', 403);
        }

        $reply->thread()->update([
            'best_reply_id' => $reply->id,
        ]);

        return response('Reply marked as best.', 200);
    }

    public function unMarkAsBest(Reply $reply)
    {
        if ($reply->thread->user_id != auth()->id()) {
            return response('You can not un mark this reply.', 403);
        }

        $reply->thread()->update([
            'best_reply_id' => null,
        ]);

        return response('Reply has been un marked.', 200);
    }
}
