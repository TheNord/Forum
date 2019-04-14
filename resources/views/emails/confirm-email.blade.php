@component('mail::message')
# One last step

We just need you to confirm your email address

@component('mail::button', ['url' => route('register.confirm', ['token' => $token])])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
