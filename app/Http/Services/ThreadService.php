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

        return Thread::latest();
    }
}