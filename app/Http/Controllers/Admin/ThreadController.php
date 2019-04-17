<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Thread;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function lock(Channel $channel, Thread $thread)
    {
       $this->checkAccess();

        $thread->lock();

        return response('Thread has been locked.', 200);
    }

    public function unlock(Channel $channel, Thread $thread)
    {
        $this->checkAccess();

        $thread->unlock();

        return response('Thread has been unlocked.', 200);
    }

    private function checkAccess()
    {
        if (Gate::denies('is-admin', auth()->user())) {
            abort(403);
        }
    }
}
