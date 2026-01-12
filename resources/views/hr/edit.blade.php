@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Edit HR
        </div>
        <form action="{{ route('hr.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('hr.partials.form')
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Update HR
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-js')
<script>
    $(document).ready(function() {
        // Loader on submit
        $('form').on('submit', function() {
            showLoader();
        });
    });
</script>
@endsection
