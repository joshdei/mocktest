@component('mail::message')
Hi {{ $userName }},

Your weekly Question of the Day is now live on your dashboard.

Answer correctly to earn ₦10 credited instantly to your wallet.

@component('mail::button', ['url' => route('daily.question.show')])
Answer Now →
@endcomponent

This question is only available today. Don't miss it!

Thanks,
{{ config('app.name') }}
@endcomponent

