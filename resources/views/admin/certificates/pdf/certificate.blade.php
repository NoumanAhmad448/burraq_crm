<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .certificate-container {
            border: 12px solid #0c3c78;
            padding: 40px;
            background: #ffffff;
            height: 100%;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0c3c78;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 34px;
            font-weight: bold;
            color: #0c3c78;
        }

        .certificate-title {
            font-size: 26px;
            margin-top: 10px;
            letter-spacing: 2px;
        }

        .content {
            margin-top: 40px;
            text-align: center;
        }

        .content p {
            font-size: 18px;
            line-height: 1.8;
            margin: 12px 0;
        }

        .student-name {
            font-size: 30px;
            font-weight: bold;
            margin: 20px 0;
            color: #000;
        }

        .course-name {
            font-size: 22px;
            font-weight: bold;
            color: #0c3c78;
        }

        .payment-table {
            margin: 40px auto 0;
            width: 80%;
            border-collapse: collapse;
        }

        .payment-table th,
        .payment-table td {
            border: 1px solid #0c3c78;
            padding: 8px 12px;
            text-align: center;
            font-size: 16px;
        }

        .payment-table th {
            background-color: #0c3c78;
            color: #fff;
        }

        .footer {
            margin-top: 100px;
            display: table;
            width: 100%;
        }

        .footer .left,
        .footer .right {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
        }

        .signature {
            border-top: 2px solid #000;
            width: 250px;
            text-align: center;
            padding-top: 6px;
            font-size: 14px;
        }

        .issued-date {
            font-size: 14px;
            color: #555;
        }

        .seal {
            text-align: right;
            font-size: 14px;
            font-style: italic;
            color: #0c3c78;
        }
    </style>
</head>

<body>
<div class="certificate-container">

    {{-- Header --}}
    <div class="header">
        <div class="company-name">
            {{ $companyName }}
        </div>
        <div class="certificate-title">
            Certificate of Completion
        </div>
    </div>

    {{-- Content --}}
    <div class="content">
        <p>This is to certify that</p>

        <div class="student-name">
            {{ $student->name }}
        </div>

        <p>
            has successfully completed the course
        </p>

        <div class="course-name">
            {{ $enrolledCourse->course->name ?? 'N/A' }}
        </div>

        <p>
            Certificate ID: {{ $certificate->id }}
        </p>

        {{-- Payment Details --}}
        @if(count($payments) > 0)
        <table class="payment-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Amount Paid</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ number_format($payment->paid_amount, 2) }}</td>
                        <td>{{ ucfirst("Cash") }}</td>
                        <td>{{ \App\Classes\LyskillsCarbon::parse($payment->created_at)->toFormattedDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p>No payment records found.</p>
        @endif

    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="left">
            <div class="signature">
                Authorized Signature
            </div>
            <div class="issued-date">
                Issued on: {{ $issuedDate }}
            </div>
        </div>

        <div class="right seal">
            Official Certificate<br>
            {{ $companyName }}
        </div>
    </div>

</div>
</body>
</html>
