@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4>Inquiry Leads Dashboard</h4>
            </div>
        </div>

        <div class="row mt-4">

            @foreach ($inquiryCounts as $item)
                <div class="col-md-3">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <a href="{{ route('inquiries.index', ['course_id' => $item->course_id]) }}"
                                class="">
                                <section>
                                    <h5>
                                        {{ $item->course->name ?? 'Unknown Course' }}
                                    </h5>

                                    <h2 class="text-primary">
                                       Total |  {{ $item->total }}
                                    </h2>
                                </section>
                            </a>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
