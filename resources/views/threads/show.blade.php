@extends('layouts.app')

@section('content')
    <thread-view inline-template :initial-replies-count="{{ $thread->replies_count  }}" :thread-body="{{ json_encode($thread->body) }}" v-cloak>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            @if ($thread->creator->hasAvatar())
                                <img src="{{ asset("storage/{$thread->creator->avatar_path}") }}" alt="user_avatar" height="25" width="25" class="mr-1">
                            @endif

                            {{ $thread->title }}

                            @if($thread->isOwner() && !$thread->hasReplies())
                                <div class="float-right">
                                    <button class="btn-icn thread-icon" @click.prevent="editThread"><i class="fa fa-pencil-alt icn-edit"></i></button>

                                    <form action="{{ route('threads.delete', [$thread->channel, $thread]) }}"
                                          method="post" class="thread-icon">
                                        @csrf
                                        @method('delete')

                                        <button class="btn-icn" type="submit"><i class="far fa-trash-alt icn-delete"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <div v-if="!editing" v-html="threadBody"></div>
                            <div v-else>
                                <div class="form-group">
                                    <wysiwyg v-model="threadBody" placeholder="Have something to say?..."></wysiwyg>
                                </div>

                                <button class="btn btn-danger" @click.prevent="updateThread">Save</button>
                                <button class="btn btn-primary" @click.prevent="editing = false">Close</button>
                            </div>
                        </div>

                    </div>

                    <replies :thread="{{ $thread }}" :channel="{{ $thread->channel }}" @removed="repliesCount--"></replies>

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

                            <p>Thread Visits: {{ $thread->visits }}</p>

                            @auth
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                            @endauth

                            @can('is-admin')
                                <locked-button :locked="{{ json_encode($thread->locked) }}"></locked-button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}">
@endsection
