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
    public function __construct()
    {
        $this->middleware('auth')->except('profile');
    }

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

    public function edit()
    {
        $user = auth()->user();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name,' . auth()->id()
        ]);

        auth()->user()->update([
            'name' => $request->name,
        ]);

        if ($request->avatar) {
            $this->uploadAvatar($request);
        }

        return back();
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image'
        ]);

        auth()->user()->update([
           'avatar_path' => $request->file('avatar')->store('avatars', 'public')
        ]);
    }
}
