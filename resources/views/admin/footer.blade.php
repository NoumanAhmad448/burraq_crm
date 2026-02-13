@php
    use Illuminate\Support\Facades\Route;
@endphp

@section('footer')
@if(Route::currentRouteName() !== 'login')
        <footer class="text-center bg-website p-3">
            <p>CRM All rights are reserved.</p>
            <p class="text-center">Powered By <a class="text-white border-bottom border-black" href="https://sites.google.com/view/noumanwebsitebuilder/home"> Nouman Website Builder </a></p>
        </footer>
    @endif

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('page-js')
    </body>

    </html>
@endsection
