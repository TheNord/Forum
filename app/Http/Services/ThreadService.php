<?php

namespace App\Http\Services;


use App\Thread;
use App\User;
use Illuminate\Http\Request;

class ThreadService
{
    public function filterThread(Request $request)
    {
        if ($request->has('by')) {
            $user = User::whereName($request->by)->first();
            return Thread::where(['user_id' => $user->id]);
        }

        if ($request->has('popular')) {
            return Thread::orderBy('replies_count', 'desc');
        }

        return Thread::latest();
    }

    public function deleteThread(Thread $thread)
    {
        if (!$thread->isOwner()) {
            throw new \LogicException('You can not delete the thread');
        }

        if ($thread->hasReplies()) {
            throw new \LogicException('Can not delete topics with replies');
        }

        $thread->delete();
    }
}