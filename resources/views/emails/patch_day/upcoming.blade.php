@component('mail::message')
    @php
        $carbon = Carbon\Carbon::parse($patch_day->date);
    @endphp
# Upcoming PatchDay

### Hello {{ $user->name }},

We have a PatchDay upcoming on {{ $carbon->format('l\, \t\h\e jS \o\f F') }}.

@component('mail::button', [
    'url' => url('/') . "/#/patch-days/{$patch_day->id}"
])
Sign up now
@endcomponent

Thanks,<br>
Your {{ config('app.name') }} Team
@endcomponent
