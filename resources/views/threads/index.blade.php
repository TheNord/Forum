@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($threads as $thread)
                    <div class="card mt-4">
                        <div class="card-header"><a href="{{ route('threads.show', $thread->id) }}">{{ $thread->title }}</a></div>
                        <div class="card-body">
                            <article>
                                <div class="body">{{ $thread->body }}</div>
                            </article>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
