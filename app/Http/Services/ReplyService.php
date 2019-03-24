<?php

namespace App\Http\Services;


use App\Reply;
use App\User;
use Illuminate\Http\Request;

class ReplyService
{
    public function deleteReply(Reply $reply)
    {
        if (!$reply->isOwner()) {
            throw new \LogicException('You not have permission for this action');
        }

        if ($reply->favorites_count) {
            throw new \LogicException('Can not delete favorited post');
        }

        $reply->delete();
    }
}