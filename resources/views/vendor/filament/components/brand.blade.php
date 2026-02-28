@if (filled($brand = config('filament.brand')))
    <div @class([
        'filament-brand text-xl font-bold leading-5 tracking-tight',
        'dark:text-white' => config('filament.dark_mode'),
    ])>
        {{ $brand }}
    </div>
@elseif (config('filament.brand_logo'))
    <a @class([
        'filament-brand leading-5 tracking-tight',
        'dark:text-white' => config('filament.dark_mode'),
    ]) href="{{ config("filament.home_url") }}">
        <img src="{{ config('filament.brand_logo') }}" alt="Website Logo" class="img-fluid mx-auto d-block"
            width="{{ config('filament.brand_logo_width') }}">
    </a>
@endif
