<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Enrollment & Fee Record</title>

    <style>
        .logo {
            display: block;
            margin: 0 auto;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
        }

        .section {
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        table th {
            background-color: #f5f5f5;
            text-align: left;
            width: 30%;
        }

        .sub-table th {
            width: auto;
        }

        .text-muted {
            color: #777;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <h2>Burraq CRM - Fee Receipt</h2>
            <p class="text-muted"> {{ now() }}</p>
        </div>
        <hr />

        {{-- <img
    src="{{ asset(config('setting.img_logo_path')) }}"
    alt="lyskills"
    class="logo"
    width="40" /> --}}

        <!-- STUDENT INFORMATION -->
        <div class="section">
            <div class="section-title">Student Information</div>

            <table>
                {{-- <tr>
                <th>Student ID</th>
                <td>{{ $student->id }}</td>
            </tr> --}}
                <tr>
                    <th>Name</th>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <th>Father Name</th>
                    <td>{{ $student->father_name }}</td>
                </tr>
                <tr>
                    <th>Cnic</th>
                    <td>{{ $student->cnic }}</td>
                </tr>
                {{-- <tr>
                <th>Mobile</th>
                <td>{{ $student->mobile }}</td>
            </tr> --}}
                {{-- <tr>
                <th>Email</th>
                <td>{{ $student?->email}}</td>
            </tr> --}}
            </table>
        </div>

        <!-- FINANCIAL SUMMARY -->
        {{-- <div class="section">
        <div class="section-title">Financial Summary</div>

        <table>
            <tr>
                <th>Total Fee</th>
                <td class="text-right">{{ number_format($student->total_fee, 2) }}</td>
            </tr>
            <tr>
                <th>Paid Fee</th>
                <td class="text-right">{{ number_format($student->paid_fee, 2) }}</td>
            </tr>
            <tr>
                <th>Remaining Fee</th>
                <td class="text-right">{{ number_format($student->remaining_fee, 2) }}</td>
            </tr>
        </table>
    </div> --}}

        <!-- ENROLLED COURSES -->
        <div class="section">
            {{-- <div class="section-title">Enrolled Courses & Payments</div> --}}

            @if ($student?->enrolledCourses?->isEmpty())
                <p class="text-muted">No enrolled courses found.</p>
            @else
                @foreach ($student->enrolledCourses as $index => $enrolledCourse)
                    <h2> Course: {{ $enrolledCourse->course->name }} </h2>
                    <div> Total Fee: {{ show_payment($enrolledCourse->total_fee) }} </div>
                    <div> Admission Date: {{ showWebPageDate($enrolledCourse->admission_date) }} </div>
                    @if(!empty($enrolledCourse->due_date))
                        <div> Due Date: {{ showWebPageDate($enrolledCourse->due_date) }} </div>
                    @endif
                    @if(max($enrolledCourse->total_fee - $enrolledCourse->payments->sum('paid_amount'), 0) > 9)
                    <div> Remaining Fee:
                        {{ show_payment(max($enrolledCourse->total_fee - $enrolledCourse->payments->sum('paid_amount'), 0)) }} </div>
                    @endif
                    <h5> Payment History </h5>
                    <table>
                        <thead>
                            <tr>
                            <tr>
                                <th>Paid Amount</th>
                                <th>Paid At</th>
                                <th>Received By</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrolledCourse->payments as $payment)
                                <tr>
                                    <td class="text-left">{{ show_payment($payment->paid_amount, 2) }}</td>
                                    <td>{{ showWebPageDate($payment->paid_at) }}</td>
                                    <td>{{ $payment->paidBy?->name ?? 'System' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach

            @endif
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Generated on {{ now()->format('d M Y, h:i A') }}
        </div>

    </div>

</body>

</html>
