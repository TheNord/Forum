<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\Reply\CreateRequest;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function show(Channel $channel)
    {
        $threads = Thread::where('channel_id', $channel->id)->get()->sortByDesc('created_at');
        return view('threads.channel', compact('threads'));
    }
}
