@component('mail::message')

    <p><b>Hello, </b> {!! $Data['name'] !!}!</p><br>
    <p><b>Subject:</b> {!! $Data['message_subject'] !!}</p>

    @component('mail::panel')
        <div class="text-justify">
            <p><b>Message:</b> {!! $Data['message_body'] !!}</p>
        </div>
    @endcomponent

    @php
        $url = route('index');
    @endphp

    @component('mail::button', ['url' => $url, 'color' => 'green'])
        Go back to website
    @endcomponent

    <br>
    <hr>

    <h3>Thanks for getting connected to us,<br>
        {{ config('app.name') }}
    </h3>

@endcomponent
