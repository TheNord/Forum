@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($threads->count())
                    @foreach($threads as $thread)
                        <article>
                            <div class="card mt-4">
                                <div class="card-header">
                                    <a href="{{ route('threads.show', [$thread->channel, $thread->id]) }}">
                                        {{ $thread->title }}
                                    </a>

                                    <strong class="float-right">{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong>
                                </div>
                                <div class="card-body">
                                    <div class="body">{{ $thread->body }}</div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <p>This user has no created threads.</p>
                @endif
                <div class="mt-4">
                    {{ $threads->links() }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mt-4">
                    <div class="card-header">
                        Profile by <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="card-body">
                        <p>Registered {{ $user->created_at->diffForHumans() }}</p>
                        <p>Created {{ $threads->count() }} {{ str_plural('thread', $threads->count()) }}</p>
                        <p>Added {{ $replies_count }} {{ str_plural('replies', $replies_count) }}</p>
                        <p>Get {{ $favorites_count }} {{ str_plural('like', $favorites_count) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
