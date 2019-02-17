<div class="card mt-4">
    <div class="card-header">
        <a href="#">
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
    </div>
</div>