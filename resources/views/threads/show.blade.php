@extends('layouts.app')

@section('content')
    <thread-view inline-template :initial-replies-count="{{ $thread->replies_count  }}">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            {{ $thread->title }}

                            @if($thread->isOwner())
                                <div class="float-right">
                                    <form action="{{ route('threads.delete', [$thread->channel, $thread]) }}"
                                          method="post">
                                        @csrf
                                        @method('delete')

                                        <button class="btn-icn" type="submit"><i
                                                    class="far fa-trash-alt icn-delete"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>

                    <replies :thread="{{ $thread }}" :channel="{{ $thread->channel }}" @removed="repliesCount--"></replies>

                    {{--<div class="mt-4">--}}
                        {{--{{ $replies->links() }}--}}
                    {{--</div>--}}

                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }}
                                by
                                <a href="{{ route('user.profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                                and currently has <span v-text="repliesCount"></span>
                                {{ str_plural('reply', $thread->replies_count) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
