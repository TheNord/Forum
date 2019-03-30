@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                    @forelse($activities as $date => $activity)
                        <h3 class="page-header">{{ $date }}</h3>

                        @foreach ($activity as $record)
                            @include ("users.activities.{$record->type}", ['activity' => $record])
                        @endforeach
                    @empty
                        <p>This user has not yet had an activity.</p>
                    @endforelse
            </div>

            <div class="col-md-4">
                <div class="card mt-4">
                    <div class="card-header">
                        Profile by <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="card-body">
                        <p>Registered {{ $user->created_at->diffForHumans() }}</p>
                        <p>Created {{ $threads_count }} {{ str_plural('thread', $threads_count) }}</p>
                        <p>Added {{ $replies_count }} {{ str_plural('replies', $replies_count) }}</p>
                        <p>Get {{ $favorites_count }} {{ str_plural('like', $favorites_count) }}</p>
                        <hr>
                        <p class="mt-2"><a href="{{ route('threads.index') }}/?by={{ $user->name }}">User threads</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
