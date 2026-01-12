@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Add HR Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Add HR
                </div>
                <form action="{{ route('hr.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('hr.partials.form')
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add HR
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- HR List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    HR List
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="crm_hr">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hrs as $hr)
                            {{-- {{dd($hr)}} --}}
                            <tr class=@if($hr->is_deleted) "text-danger" @endif>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $hr->name }}</td>
                                <td>{{ $hr->email }}</td>
                                <td>{{ $hr->profile->mobile_no ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('hr.edit', $hr->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('hr.logs') }}?user_id={{ $hr->id }}" class="btn btn-sm btn-secondary" title="View Logs">
                                        <i class="fa fa-history"></i>
                                    </a>
                                    @if(auth()->user()->is_admin)
                                    <a href="{{ route('hr.destroy', $hr->id) }}" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this HR?')"
                                        title="Delete">
                                    <i class="fa fa-trash"></i>
                                    </a>
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
    $(document).ready(function() {
        new simpleDatatables.DataTable("#crm_hr", {
            searchable: true,
            perPage: 10
        });

        // Loader example on form submit
        $('form').on('submit', function() {
            showLoader();
        });
    });
</script>
@endsection
