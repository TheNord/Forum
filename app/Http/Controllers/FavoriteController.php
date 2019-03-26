<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        try {
            $reply->favorite(auth()->id());
            return response('Ok', 201);
        } catch (\Exception $e) {
            return response($e->getMessage(), 403);
        }
    }

    public function destroy(Reply $reply)
    {
        try {
            $reply->unFavorite(auth()->id());
            return response('Ok', 201);
        } catch (\Exception $e) {
            return response($e->getMessage(), 403);
        }
    }
}
