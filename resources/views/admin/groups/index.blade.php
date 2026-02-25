@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid">
        @include('messages')
        <x-admin>
            <h2>Assign Instructors</h2>
            @include('admin.groups.ins_form')
            <hr>
        </x-admin>

        <!-- Assign Student Form -->
        @include('admin.groups.ass_stu')
        <hr>
        <div class="d-flex justify-content-between">
            <h3>Groups</h3>
            <a href="{{ route('admin.groups.create') }}" class="btn btn-success mb-2"> <i
                    class="fa fa-pencil"></i> Create Group</a>
        </div>

        {{-- Group Filters --}}
        @include("admin.groups.group_filter")
        <!-- Group Table -->
        <table id="crm_groups" class="table table-bordered">
            @include('admin.groups.group_tble_heading')
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>
                            <a class="underscore text-primary"
                                href="{{ allowCourseToAdmin() ? route('students.index', ['group_id' => $group->id]) : '' }}">
                                {{ humanize($group->group_name) }}
                            </a>
                        </td>
                        <td>{{ $group->timing }}</td>
                        <td>
                            @foreach ($group->instructors as $instr)
                                {{ $instr->name }}
                                @if($instr->enrolledGroup?->count()) 
                                 - Group Count ( 
                                    {{ $instr->enrolledGroup?->count() }}
                                     {{ ")" }}
                                 @endif
                                 @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td>
                            {{ $group->groupEnrollment?->count() }}
                        </td>
                        <td>{{ $group->status }}</td>
                        <td>{{ showWebPageDate($group->created_at) }}</td>
                        @include('admin.groups.group_action')
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('page-js')
    @include('export_to_excel', ['id' => '#crm_groups', "placeholder" => "Search Groups ..."])
@endsection
