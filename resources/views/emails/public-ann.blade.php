@component('mail::message')
    Hi {{ $name }}

    {!! $body !!}

    @component('mail::button', ['url' => 'https://burraqengineering.com'])
        CRM
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
