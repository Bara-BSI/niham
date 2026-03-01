<x-mail::message>
# {{ __('messages.hello') }} {{ $user->name }},

{{ __('messages.here_is_your_automated_notification_digest_from') }} {{ config('app.name') }}.

@foreach ($notifications as $notification)
<x-mail::panel>
**{{ $notification->data['message'] ?? 'Notification' }}**
@if(isset($notification->data['changes']))
@foreach($notification->data['changes'] as $key => $value)
- **{{ ucfirst($key) }}**: {{ $value }}
@endforeach
@endif
<br><small>{{ $notification->created_at->diffForHumans() }}</small>
</x-mail::panel>
@endforeach

<x-mail::button :url="url('/dashboard')">
{{ __('messages.go_to_dashboard') }}
</x-mail::button>

{{ __('messages.thanks') }}<br>
{{ config('app.name') }}
</x-mail::message>
