@extends('admin.admin_main')

@section('content')

@endsection

@section('script')
    @if (config('setting.aos_js'))
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            AOS.init();
        </script>
    @endif
@endsection
