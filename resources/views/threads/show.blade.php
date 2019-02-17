@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ $thread->title }}
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                <div class="mt-4">
                    {{ $replies->links() }}
                </div>


                <div class="mt-4">
                    <h2>New reply</h2>
                    @if (auth()->check())
                        <form action="{{ route('reply.store', [$thread->channel, $thread->id]) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="body"></label>
                                <textarea name="body" id="body"
                                          class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}"
                                          rows="6" placeholder="Have something to say?">{{ old('body') }}</textarea>
                                @if ($errors->has('body'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('body') }}</strong></span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-success">Post</button>
                        </form>
                    @else
                        <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }}
                            by <a href="#">{{ $thread->creator->name }}</a> and currently has {{ $thread->replies_count }}
                            {{ str_plural('reply', $thread->replies_count) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
