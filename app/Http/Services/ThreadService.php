<?php

namespace App\Http\Services;


use App\Thread;
use App\User;
use Illuminate\Http\Request;

class ThreadService
{
    /**
     * Filter thread by request queries params
     *
     * @param Request $request
     * @return mixed
     */
    public function filterThread(Request $request)
    {
        if ($request->has('by')) {
            $user = User::whereName($request->by)->first();
            return Thread::where(['user_id' => $user->id]);
        }

        if ($request->has('popular')) {
            return Thread::orderBy('replies_count', 'desc');
        }

        if ($request->has('unanswered')) {
            return Thread::where('replies_count', 0);
        }

        return Thread::latest();
    }

    /**
     * Update thread body
     *
     * @param Request $request
     * @param Thread $thread
     * @throws \Throwable
     */
    public function update(Request $request, Thread $thread)
    {
        throw_unless($thread->isOwner(), '\RuntimeException', 'You can not edit this thread');
        throw_if($thread->locked, '\RuntimeException', 'Locked thread can not be updated');
        throw_if($thread->hasReplies(), '\RuntimeException', 'Thread with replies can not be updated');

        $thread->update(['body' => $request->body]);
    }

    /**
     * Delete thread without reply
     *
     * @param Thread $thread
     * @throws \Throwable
     */
    public function deleteThread(Thread $thread)
    {
        throw_unless($thread->isOwner(), '\LogicException', 'You can not delete the thread');
        throw_if($thread->hasReplies(), '\LogicException', 'Can not delete topics with replies');

        $thread->delete();
    }
}