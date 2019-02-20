<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Channel;
use App\Favorite;
use App\Http\Requests\Reply\CreateRequest;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(User $user)
    {
        $threads_count = Thread::where('user_id', $user->id)->count();
        $replies_count = Reply::where('user_id', $user->id)->count();
        $favorites_count = Favorite::where('favorited_id', $user->id)->count();
        $activities = Activity::feed($user);

        return view('users.profile',
            compact(
                'user',
                'threads_count',
                'replies_count',
                'favorites_count',
                'activities'
            )
        );
    }
}
