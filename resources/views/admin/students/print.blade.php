<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Enrollment & Fee Record</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
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
            font-size: 15px;
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
        <h2>Burraq Engineering</h2>
        <p class="text-muted">Student Enrollment & Fee Record</p>
    </div>

    <!-- STUDENT INFORMATION -->
    <div class="section">
        <div class="section-title">Student Information</div>

        <table>
            <tr>
                <th>Student ID</th>
                <td>{{ $student->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <th>Father Name</th>
                <td>{{ $student->father_name }}</td>
            </tr>
            <tr>
                <th>CNIC</th>
                <td>{{ $student->cnic }}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{ $student->mobile }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $student->email ?? '—' }}</td>
            </tr>
            <tr>
                <th>Admission Date</th>
                <td>{{ $student->admission_date }}</td>
            </tr>
            <tr>
                <th>Due Date</th>
                <td>{{ $student->due_date ?? '—' }}</td>
            </tr>
        </table>
    </div>

    <!-- FINANCIAL SUMMARY -->
    <div class="section">
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
    </div>

    <!-- ENROLLED COURSES -->
    <div class="section">
        <div class="section-title">Enrolled Courses & Payments</div>

        @if($student?->enrolledCourses?->isEmpty())
            <p class="text-muted">No enrolled courses found.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Name</th>
                        <th>Total Fee</th>
                        <th>Payments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->enrolledCourses as $index => $enrolledCourse)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $enrolledCourse->course->name }}</td>
                            <td class="text-right">{{ number_format($enrolledCourse->total_fee, 2) }}</td>
                            <td>
                                @if ($enrolledCourse->payments->isNotEmpty())
                                    <table class="sub-table">
                                        <thead>
                                            <tr>
                                                <th>Paid Amount</th>
                                                <th>Paid At</th>
                                                <th>Payment By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($enrolledCourse->payments as $payment)
                                                <tr>
                                                    <td class="text-right">{{ number_format($payment->paid_amount, 2) }}</td>
                                                    <td>{{ $payment->paid_at }}</td>
                                                    <td>{{ $payment->paidBy?->name ?? 'System' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <span class="text-muted">No payments recorded</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>

</div>

</body>
</html>
