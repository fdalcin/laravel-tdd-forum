@component('mail::message')
# One Last Step

We just need you to confirm your e-mail address to prove that you're a human. You get it, right?

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm e-mail
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
