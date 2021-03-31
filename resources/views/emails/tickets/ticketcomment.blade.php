@component('mail::message')

### Ticket Status:
{{ $ticketdata->ticketStatus->name ?? 'Null' }}
### Ticket Message:
{{ $ticketmessage->message ?? 'Your ticket query is closed thank you for contacting us!'}}

Thanks,<br>
{{ auth()->user()->name }}<br>
{{ config('app.name') }}
@endcomponent
