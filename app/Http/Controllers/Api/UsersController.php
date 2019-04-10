<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $name = request('name');

        if ($name) {
            return User::where('name', 'LIKE', "$name%")
                ->take(5)
                ->pluck('name');
        }

        return response('', 204);
    }
}
