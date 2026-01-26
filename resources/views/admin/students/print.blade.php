<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Enrollment & Fee Record</title>

    <style>
        .pdf-header {
            background-color: #0d47a1;
            /* deep professional blue */
            color: #ffffff;
            padding: 20px 30px;
            text-align: center;
            margin-bottom: 20px;
        }

        .pdf-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            position: relative;
            display: inline-block;
            padding: 0 20px;
        }

        /* Line before and after heading */
        .pdf-header h2::before,
        .pdf-header h2::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 100vw;
            /* stretch to page edge */
            height: 1px;
            background-color: rgba(255, 255, 255, 0.6);
        }

        .pdf-header h2::before {
            right: 100%;
            margin-right: 15px;
        }

        .pdf-header h2::after {
            left: 100%;
            margin-left: 15px;
        }

        .pdf-header .date {
            margin-top: 6px;
            font-size: 11px;
            opacity: 0.85;
        }

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
            font-size: 16px;
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

    <style>
        .bg{
        background-color: #0d47a1; /* deep professional blue */
        color: #ffffff;
        width: 199px;
        padding-left: 10px;
        }
        .color{
        color: #0d47a1;
        width: 199px;
        padding-left: 10px;
        }
        .pdf-header {
        background-color: #0d47a1; /* deep professional blue */
        color: #ffffff;
        padding: 20px 30px;
        text-align: center;
        margin-bottom: 20px;
        }

        .pdf-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            position: relative;
            display: inline-block;
            padding: 0 20px;
        }

        /* Line before and after heading */
        .pdf-header h2::before,
        .pdf-header h2::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 100vw;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.6);
        }

        .pdf-header h2::before {
            right: 100%;
            margin-right: 15px;
        }

        .pdf-header h2::after {
            left: 100%;
            margin-left: 15px;
        }

        .pdf-header .date {
            margin-top: 6px;
            font-size: 11px;
            opacity: 0.85;
            display: inline-block;
        }

    </style>
    <style>
        /* Date styling below header */
        .pdf-date {
            text-align: center;
            font-size: 12px;
            color: #000000;
            margin-top: 10px; /* space below header */
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <!-- HEADER -->
        <div class="pdf-header">
            <h2>BURRAQ CRM - FEE RECEIPT</h2>
        </div>

        <p class="pdf-date">
            Receipt# BES-{{ 3000 + (int) $student->id }}
        </p>
        <p class="pdf-date">
            Generated at {{ now()->format('d M Y') }}
        </p>
        <!-- STUDENT INFORMATION -->
        <div class="section">
            <div class="section-title bg">Student Information</div>

            <table>
                {{-- <tr>
                <th>Student ID</th>
                <td>{{ $student->id }}</td>
            </tr> --}}
                <tr>
                    <th class="">Name</th>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <th class="">Father Name</th>
                    <td>{{ $student->father_name }}</td>
                </tr>
                {{-- <tr>
                    <th>Cnic</th>
                    <td>{{ $student->cnic }}</td>
                </tr> --}}
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
                    <h2 class="bg"> Course: {{ $enrolledCourse->course->name }} </h2>
                    <div> Total Fee: {{ show_payment($enrolledCourse->total_fee) }} </div>
                    <div> Admission Date: {{ showWebPageDate($enrolledCourse->admission_date) }} </div>
                    @if (!empty($enrolledCourse->due_date))
                        <div> Due Date: {{ showWebPageDate($enrolledCourse->due_date) }} </div>
                    @endif
                    @if ($enrolledCourse->total_fee > $enrolledCourse->payments->sum('paid_amount'))
                        <div> Remaining Fee:
                            <small style="color: red">
                                {{ show_payment(max($enrolledCourse->total_fee - $enrolledCourse->payments->sum('paid_amount'), 0)) }}
                            </small>
                        </div>
                    @endif
                    <h2 class="bg"> Payment History </h2>
                    <table>
                        <thead>
                            <tr>
                            <tr>
                                <th>Paid Amount</th>
                                <th>Paid At</th>
                                <th>Received By</th>
                                <th>Received Method</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrolledCourse->payments as $payment)
                                <tr>
                                    <td class="text-left">{{ show_payment($payment->paid_amount, 2) }}</td>
                                    <td>{{ showWebPageDate($payment->payment_date) }}</td>
                                    <td>{{ $payment->paidBy?->name ?? 'System' }}</td>
                                    <td>{{ $payment->payment_method ?? 'System' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach

            @endif
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <style>
                .application-note {
                    padding: 10px 0; /* top & bottom padding */
                    font-size: 12px; /* optional: readable in PDF */
                    line-height: 1.5;
                }
                </style>
            <hr>
            <p class="application-note">
                <strong>Note:</strong> The application fee is a monetary payment to the institute and must be submitted along with the application for enrollment. Application fees are generally non-refundable, even if the application is either rejected or enrollment is cancelled.
            </p>
            <hr>
            Generated on {{ now()->format('d M Y, h:i A') }}
        </div>

    </div>

</body>

</html>
