<reply inline-template v-cloak :attributes="{{ $reply }}">
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
                <div class="body">
                    <div v-if="editing">
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="5" v-model="body"></textarea>
                        </div>

                        <button class="btn btn-sm btn-primary" @click="update">Update</button>
                        <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
                    </div>
                    <div v-else v-text="body"></div>
                </div>
            </article>

            @if ($reply->isOwner())
                <hr/>

                <div class="float-right btn-group">
                    <button class="btn-icn mr-3" @click="editing = true"><i class="fa fa-pencil-alt icn-edit "></i></button>

                    <form action="{{ route('reply.delete', [$thread->channel, $thread, $reply]) }}" method="post">
                        @csrf
                        @method('delete')

                        <button class="btn-icn" type="submit"><i class="far fa-trash-alt icn-delete"></i></button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</reply>