@if(auth()->check() && auth()->user()->isSuperAdmin())
    {{ $slot }}
@endif
