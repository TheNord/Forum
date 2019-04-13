@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($threads as $thread)
                    <article>
                        <div class="card mt-4">
                            <div class="card-header">
                                <a href="{{ route('threads.show', [$thread->channel, $thread->id]) }}">
                                    @if ($thread->hasUpdatedFor())
                                        <strong>
                                            {{ $thread->title }}
                                        </strong>
                                    @else
                                        {{ $thread->title }}
                                    @endif
                                </a>

                                <strong class="float-right">{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong>

                                <br/>
                                Posted by: <a
                                        href="{{ route('user.profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a>
                            </div>
                            <div class="card-body">
                                <div class="body">{{ $thread->body }}</div>
                            </div>
                        </div>
                    </article>
                @endforeach
                <div class="mt-4">
                    {{ $threads->links() }}
                </div>
            </div>

            @if(count($trending))
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="card mt-4">
                                <div class="card-header">
                                    Trending Threads
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach ($trending as $thread)
                                            <a href="{{ url($thread->path)  }}">
                                                <li class="list-group-item">{{ $thread->title }}</li>
                                            </a>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
