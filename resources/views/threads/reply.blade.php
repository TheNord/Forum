<div id="reply-{{ $reply->id }}" class="card mt-4">
    <div class="card-header">
        <a href="{{ route('user.profile', $reply->owner) }}">
            {{ $reply->owner->name }}
        </a>
        said {{ $reply->created_at->diffForHumans() }}...
        <div class="float-right">
            <form action="{{ route('reply.favorite', $reply) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-outline-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }} favorites
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <article>
            <div class="body">{{ $reply->body }}</div>
        </article>

        @if ($reply->isOwner())
            <hr/>

            <div class="float-right">
                <form action="{{ route('reply.delete', [$thread->channel, $thread, $reply]) }}" method="post">
                    @csrf
                    @method('delete')

                    <button class="btn-icn" type="submit"><i class="far fa-trash-alt icn-delete"></i></button>
                </form>
            </div>
        @endif
    </div>
</div>