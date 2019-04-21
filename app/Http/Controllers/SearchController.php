<?php

namespace App\Http\Controllers;

use App\Service\TrendingService;
use App\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Request $request, TrendingService $trending)
    {
        $threads =  Thread::search($request->q)->paginate(10);

        if ($request->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }
}
