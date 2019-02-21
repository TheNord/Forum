@component('users.activities.activity')
    @slot('heading')
        {{ $user->name }}
        <a href="{{ route('reply.show', [
            $activity->subject->favorited->thread->channel,
            $activity->subject->favorited->thread,
            $activity->subject->favorited->id
        ]) }}">
            favorited a reply
        </a>
        by user {{ $activity->subject->favorited->owner->name }}
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent