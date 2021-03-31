@component('mail::message')
# Your Ticket details are:

Ticket Description: {{$ticketdata->description}}

@if($ticketdata->ticket_image != Null)
<img class="avatar" src="{{ asset('/storage/'.$ticketdata->ticket_image) }}" alt="ticket_image">
@endif

Thanks,<br>
{!! ($ticketdata->user_id != Null) ? auth()->user()->name.' <br>' : '' !!}
{{ config('app.name') }}
@endcomponent
