<?php

namespace App\Http\Services;


use App\Http\Resources\ReplyResource;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyService
{
    /**
     * @param Reply $reply
     * @throws \Throwable
     */
    public function deleteReply(Reply $reply)
    {
        throw_unless($reply->isOwner(), '\LogicException', 'You not have permission for this action');
        throw_if($reply->favorites_count, '\LogicException', 'Can not delete favorited post');

        $reply->delete();
    }

    /**
     * Store new reply to database
     *
     * @param Request $request
     * @param Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request, Thread $thread)
    {
        throw_if(Gate::denies('create', new Reply), '\RuntimeException', 'You are posting too frequently. Please wait.', 403);

        $reply = $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return response(new ReplyResource($reply), 200);
    }
}