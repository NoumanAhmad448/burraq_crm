@extends('admin.admin_main')
@section('page-css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> 
    .amount-orange{
  color: #fd7e14
}
</style>
@endsection
@section('content')
 @php

     $info = [
         [
             'title' => 'Total Students',
             'count' => $activeStudents,
             'icon' => 'img/Total_Studenet.png',
             'amount_color' => 'success',
             'route' => 'students.index',
         ],

         [
             'title' => 'Students (This Month)',
             'count' => $studentsThisMonth->sum("total"),
             'icon' => 'img/fa-fa-users.png',
             'bg' => 'bg-primary',
             'amount_color' => 'purple',
             'route' => 'students.index',
             'route_keys' => ['month' => $month, 'year' => $year],
         ],

         [
             'title' => 'Total Overdue Students',
             'count' => $totalOverdue_count,
             'icon' => 'img/fa-fa-users_o.png',
             'bg' => 'bg-danger',
             'amount_color' => 'purple',
             'route' => 'students.index',
             'route_keys' => ['type' => 'overdue'],
         ],

         [
             'title' => 'Total Pending Students',
             'count' => $totalUnpaid_count,
             'icon' => 'img/fa-fa-users-u.png',
             'bg' => 'bg-primary',
             'amount_color' => 'success',
             'route' => 'students.index',
             'route_keys' => ['type' => 'unpaid'],
         ],
         [
             'title' => 'Total Payments (This Month)',
             'count' => show_payment($paymentsThisMonth),
             'icon' => 'img/fa-fa-money.png',
             'bg' => 'bg-success',
             'amount_color' => 'success',
             'route' => null,
             'roles' => ['admin'],
         ],

         [
             'title' => 'Paid Payments (This Month)',
             'count' => show_payment($totalPaid_m),
             'icon' => 'img/fa-fa-money10.png',
             'bg' => 'bg-success',
             'amount_color' => 'success',
             'route' => null,
             'roles' => ['admin'],
         ],

         [
             'title' => 'Pending Payments (This Month)',
             'count' => show_payment($pendingThisMonth),
             'icon' => 'img/fa-fa-money2.png',
             'bg' => 'bg-success',
             'amount_color' => 'orange',
             'route' => null,
             'roles' => ['admin'],
         ],

         [
             'title' => 'Overdue Payments (This Month)',
             'count' => show_payment($dueThisMonth),
             'icon' => 'img/fa-fa-money3.png',
             'bg' => 'bg-success',
             'amount_color' => 'danger',
             'route' => null,
             'roles' => ['admin'],
         ],
         [
             'title' => 'Total Income (This Month)',
             'count' => show_payment($paymentsThisMonth + $pendingThisMonth),
             'icon' => 'img/fa-fa-check-circle.png',
             'bg' => 'bg-info',
             'amount_color' => 'success',
             'route' => 'students.index',
             'roles' => ['admin'],
         ],
         [
             'title' => 'Total Income',
             'count' => show_payment($totalPaid_g+$totalUnpaid),
             'icon' => 'img/fa-fa-check-circle.png',
             'bg' => 'bg-info',
             'amount_color' => 'success',
             'route' => 'students.index',
             'roles' => ['admin'],
         ],

         [
             'title' => 'Total Payment',
             'count' => show_payment($totalPaid_g),
             'icon' => 'img/fa-fa-check-circle1.png',
             'bg' => 'bg-success',
             'amount_color' => 'success',
             'route' => 'students.index',
             'roles' => ['admin'],
         ],

         [
             'title' => 'Total Paid Payment',
             'count' => show_payment($totalPaid),
             'icon' => 'img/fa-fa-check-circle2.png',
             'bg' => 'bg-info',
             'amount_color' => 'success',
             'route' => 'students.index',
             'route_keys' => ['type' => 'paid'],
             'roles' => ['admin'],
         ],

         [
             'title' => 'Total Pending Payment',
             'count' => show_payment($totalUnpaid),
             'icon' => 'img/fa-fa-thumbs-up.png',
             'bg' => 'bg-info',
             'amount_color' => 'orange',
             'route' => 'students.index',
             'route_keys' => ['type' => 'unpaid'],
             'roles' => ['admin'],
         ],

         [
             'title' => 'Total Overdue Payments',
             'count' => show_payment($totalOverdue),
             'icon' => 'img/fa-fa-check-circle.png',
             'bg' => 'bg-info',
             'amount_color' => 'danger',
             'route' => 'students.index',
             'route_keys' => ['type' => 'overdue'],
             'roles' => ['admin'],
         ],

         [
             'title' => 'Certificate',
             'count' => $cert_count,
             'icon' => 'img/fa-fa-thumbs-up fa-2x.png',
             'bg' => 'bg-info',
             'amount_color' => 'primary',
             'route' => 'certificates.index',
             'roles' => ['admin'],
         ],
     ];

 @endphp


@include("wel_filters")
@include("course_cards")
@include("course_graph")

@endsection
@section('page-js')
<x-admin>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    @include("graph_js")
    @include("filter_js")
</x-admin>
@endsection
