<div class="card mt-4">
    <div class="card-header">
        <a href="#">
            {{ $reply->owner->name }}
        </a>
        said {{ $reply->created_at->diffForHumans() }}...
    </div>

    <div class="card-body">
        <article>
            <div class="body">{{ $reply->body }}</div>
        </article>
        <hr>
    </div>
</div>