<?php

namespace App\Http\Controllers;

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
        $threads = Thread::where('user_id', $user->id)->paginate(5);
        $replies_count = Reply::where('user_id', $user->id)->count();
        $favorites_count = Favorite::where('favorited_id', $user->id)->count();
        return view('users.profile', compact('user', 'threads', 'replies_count', 'favorites_count'));
    }
}
