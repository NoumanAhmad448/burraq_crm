@if(auth()->check() && auth()->user()->is_admin)
    {{ $slot }}
@endif
