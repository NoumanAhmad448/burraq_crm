@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="mb-0">
                <i class="fa fa-certificate"></i> Certificates
            </h4>
        </div>
    </div>

    {{-- Listing --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped" id="crm_certificates">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Cert. No</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Total Fee</th>
                        <th>Paid</th>
                        {{-- <th>Status</th> --}}
                        <th>Generated Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($enrolledCourses as $index => $row)
                    {{-- {{dd($row)}} --}}
                    @if($row['is_paid'])
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @php
                            $certificate = \App\Models\Certificate::where("enrolled_course_id", $row["enrolled_course"]->id)->where("student_id", $row["student"]->id)->latest()->first();
                            // dd($certificate);
                            @endphp
                            <td>@if($certificate?->id) BES{{ 3000 + ($certificate?->id ?  : 0) + 1 }} @endif</td>

                            <td>
                                {{ $row['student']->name ?? 'Unknown' }}

                                @if($row['student'] && $row['student']->is_deleted)
                                    <span class="badge badge-danger ml-1">
                                        Deleted
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $row['course']->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ number_format((int)$row['total_fee'], 2) }}
                            </td>

                            <td>
                                {{ number_format((int)$row['total_paid'], 2) }}
                            </td>

                            {{-- <td>
                                @if($row['is_paid'])
                                    <span class="badge badge-success">
                                        Paid
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        Pending
                                    </span>
                                @endif
                            </td> --}}

                            <td>
                                {{ $row["enrolled_course"]?->certificate?->count() ?? 0 }}
                            </td>

                            <td class="text-nowrap">
                                <a href="{{ route('students.certificate.generate', [$row['student']->id, $row['enrolled_course']->id]) }}"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fa fa-certificate"></i>
                                </a>

                                <a href="{{ route('students.certificate.logs', $row['enrolled_course']->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-history"></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No certificate records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection

@section('page-js')
<script>
    $(document).ready(function () {
        new simpleDatatables.DataTable("#crm_certificates", {
            searchable: true,
            perPage: 10
        });
    });
</script>
@endsection
