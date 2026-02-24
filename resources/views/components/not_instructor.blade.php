@if(auth()->check() && ! auth()->user()->isInstructor())
    {{ $slot }}
@endif
