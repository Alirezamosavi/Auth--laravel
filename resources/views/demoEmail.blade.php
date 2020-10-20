@component('mail::message')
{{ $mailData['title'] }}
Hello **{{$name}}**
The body of your message.mm

@component('mail::button', ['url' => $mailData['url']])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent