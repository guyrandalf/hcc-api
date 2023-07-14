@component('mail::message')
# Forgot Password - Verification Code

Your verification code is: {{ $code }}

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
