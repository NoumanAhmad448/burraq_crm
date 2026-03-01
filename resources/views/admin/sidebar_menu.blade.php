@php
$menuGroups = [

    [
        'title' => 'Academics',
        'icon'  => 'fa-graduation-cap',
        'items' => [
            [
                'title' => 'Students',
                'icon'  => 'fa-user',
                'route' => route('students.index'),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Completed Students',
                'icon'  => 'fa-graduation-cap',
                'route' => route('students.index', ["status" => "Completed"]),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Courses',
                'icon'  => 'fa-book',
                'route' => route('courses.index'),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Inquiries',
                'icon'  => 'fa-envelope',
                'route' => route('inquiries.index'),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Inquiries Dashboard',
                'icon'  => 'fa-inbox',
                'route' => route('admin.inquiry.dashboard'),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Inquiries Leads',
                'icon'  => 'fa-bullseye',
                'route' => route('inquiry_dashboard.index'),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Course Dashboard',
                'icon'  => 'fa-graduation-cap',
                'route' => route('course_dashboard.index'),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Group Management',
                'icon'  => 'fa-dashboard',
                'route' => route('admin.groups.index'),
                'access_roles' => ['admin', 'instructor', 'admission_officer'],
            ],
        ],
    ],

    [
        'title' => 'Certificates',
        'icon'  => 'fa-certificate',
        'items' => [
            [
                'title' => 'All Certificates',
                'icon'  => 'fa-certificate',
                'route' => route('certificates.index'),
                'access_roles' => ['admin', 'hr', 'admission_officer'],
            ],
            [
                'title' => 'Generated Certificates',
                'icon'  => 'fa-check-circle',
                'route' => route('certificates.index', ['type' => 'paid']),
                'img'   => 'cert_gen.png',
                'access_roles' => ['admin', 'hr', 'admission_officer', 'print_certificate'],
            ],
        ],
    ],

    [
        'title' => 'HR & Users',
        'icon'  => 'fa-user-circle',
        'items' => [
            [
                'title' => 'HR',
                'icon'  => 'fa-user-circle',
                'route' => route('hr.index'),
                'img'   => 'hr.png',
                'access_roles' => ['admin', 'hr_role'],
            ],
            [
                'title' => 'Users',
                'icon'  => 'fa-users',
                'route' => route('admin.user.index'),
                'access_roles' => ['admin'],
            ],
            [
                'title' => 'Deleted Users',
                'icon'  => 'fa-user-times',
                'route' => route('admin.user.index', ['type' => 'deleted']),
                'img'   => 'deleted_users.png',
                'access_roles' => ['admin'],
            ],
        ],
    ],

    [
        'title' => 'Recycle Bin',
        'icon'  => 'fa-trash',
        'items' => [
            [
                'title' => 'Deleted Students',
                'icon'  => 'fa-user-times',
                'route' => route('students.index', ['type' => 'deleted']),
                'img'   => 'del_stu.png',
                'access_roles' => ['admin'],
            ],
            [
                'title' => 'Deleted Courses',
                'icon'  => 'fa-book text-danger',
                'route' => route('courses.index', ['type' => 'deleted']),
                'access_roles' => ['admin'],
            ],
            [
                'title' => 'Dropped Courses',
                'icon'  => 'fa-trash text-danger',
                'route' => route('students.index', ['type' => 'dropped']),
                'access_roles' => ['admin'],
            ],
            [
                'title' => 'Dropped Groups',
                'icon'  => 'fa-user-times text-danger',
                'route' => route('admin.groups.trashed'),
                'access_roles' => ['admin'],
            ],
        ],
    ],

    [
        'title' => 'System',
        'icon'  => 'fa-cogs',
        'items' => [
            [
                'title' => 'Cron Jobs',
                'icon'  => 'fa-clock',
                'route' => route('cron-jobs.index'),
                'access_roles' => ['super_admin'],
            ],
            [
                'title' => 'Role Management',
                'icon'  => 'fa-user-shield',
                'route' => route('admin.roles.index'),
                'access_roles' => ['super_admin'],
            ],

            [
                'title' => 'Assign User Roles',
                'icon'  => 'fa-user-cog',
                'route' => route('admin.users.assign.roles'),
                'access_roles' => ['super_admin'],
            ],
            [
                'title' => 'Health Report',
                'icon'  => 'fa-user-cog',
                'route' => route('health'),
                'access_roles' => ['super_admin'],
            ],
        ],
    ],
];
@endphp
@foreach ($menuGroups as $gIndex => $group)
    @php
        $visibleItems = collect($group['items'])->filter(function ($item) {
            return empty($item['access_roles'])
                || auth()->user()->hasRoleOrSuperAdmin($item['access_roles'], false) 
                || (in_array("admin", $item['access_roles']) && isCurrentUserAdmin())
                ;
        });
    @endphp

    @if ($visibleItems->isNotEmpty())
        <li class="nav-item dropdown text-center px-3">

            <!-- Parent -->
            <a class="navbar-text text-white dropdown-toggle"
               href="#"
               id="menuDropdown{{ $gIndex }}"
               role="button"
               data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">

                <i class="fa {{ $group['icon'] }} d-block mb-1"></i>
                <small>{{ $group['title'] }}</small>
            </a>

            <!-- Children -->
            <div class="dropdown-menu dropdown-menu-right shadow-sm"
                 aria-labelledby="menuDropdown{{ $gIndex }}">

                @foreach ($visibleItems as $index => $item)
                    <a class="dropdown-item d-flex align-items-center"
                       href="{{ $item['route'] }}"
                       data-index="{{ $index }}">

                        @if(!empty($item['img']))
                            <img src="{{ asset('img/'.$item['img']) }}"
                                 width="22"
                                 class="mr-2 rounded">
                        @else
                            <i class="fa {{ $item['icon'] }} mr-2 text-primary"></i>
                        @endif

                        {{ $item['title'] }}
                    </a>
                @endforeach

            </div>
        </li>
    @endif
@endforeach