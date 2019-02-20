@component('users.activities.activity')
    @slot('heading')
        {{ $user->name }} replied to
        <a href="{{ route('threads.show', [$activity->subject->thread->channel, $activity->subject->thread]) }}">"{{ $activity->subject->thread->title }}"</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent