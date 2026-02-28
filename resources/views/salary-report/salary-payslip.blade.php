<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Slip — {{ $payment->employee->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0efe9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2.5rem 1.5rem;
            color: #1a1a18;
            font-size: 13px;
        }

        /* ── Screen buttons ── */
        .screen-only {
            margin-bottom: 1.25rem;
            display: flex;
            gap: 0.6rem;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1.1rem;
            background: #1a3a5c;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-family: 'Inter', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-print:hover { background: #142d47; }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1.1rem;
            background: #fff;
            color: #5a5a54;
            border: 1px solid #d0d0c8;
            border-radius: 5px;
            font-family: 'Inter', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-back:hover { border-color: #1a3a5c; color: #1a3a5c; }

        /* ── Slip wrapper ── */
        .slip {
            width: 100%;
            max-width: 680px;
            background: #ffffff;
            border: 1px solid #d8d8d0;
        }

        /* ── Header ── */
        .slip-header {
            padding: 1.5rem 2rem;
            border-bottom: 2px solid #1a1a18;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .company-block .company-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a1a18;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .company-block .company-sub {
            font-family: 'DM Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #9a9a92;
            margin-top: 0.3rem;
        }

        .slip-title-block { text-align: right; }

        .slip-title-block .slip-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.58rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #9a9a92;
            display: block;
            margin-bottom: 0.2rem;
        }

        .slip-title-block .slip-month {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a18;
            letter-spacing: -0.01em;
        }

        /* ── Employee Info strip ── */
        .emp-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid #e2e2dc;
        }

        .emp-cell {
            padding: 0.85rem 1.25rem;
            border-right: 1px solid #e2e2dc;
        }
        .emp-cell:last-child { border-right: none; }

        .emp-cell .ec-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.56rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #9a9a92;
            display: block;
            margin-bottom: 0.3rem;
        }

        .emp-cell .ec-val {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1a1a18;
        }

        /* ── Section label ── */
        .section-label {
            padding: 0.5rem 1.25rem;
            background: #f8f8f5;
            border-bottom: 1px solid #e2e2dc;
            font-family: 'DM Mono', monospace;
            font-size: 0.56rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #9a9a92;
        }

        /* ── Detail rows ── */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table thead tr {
            background: #f8f8f5;
            border-bottom: 1px solid #d0d0c8;
        }

        .detail-table thead th {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #5a5a54;
            padding: 0.55rem 1.25rem;
            text-align: left;
        }
        .detail-table thead th:last-child { text-align: right; }

        .detail-table tbody tr {
            border-bottom: 1px solid #eeeeea;
        }
        .detail-table tbody tr:last-child { border-bottom: none; }

        .detail-table tbody td {
            padding: 0.75rem 1.25rem;
            vertical-align: middle;
        }

        .item-name {
            font-size: 0.8rem;
            font-weight: 500;
            color: #1a1a18;
        }

        .item-note {
            font-size: 0.68rem;
            color: #9a9a92;
            margin-top: 0.15rem;
        }

        .item-amount {
            text-align: right;
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
            font-weight: 500;
            font-feature-settings: "tnum";
            white-space: nowrap;
        }

        .item-amount.earn { color: #1a5c3a; font-weight: 700; }

        .badge-type {
            display: inline-block;
            padding: 0.18rem 0.6rem;
            border-radius: 4px;
            font-size: 0.62rem;
            font-weight: 600;
            background: #e8eef4;
            color: #1a3a5c;
        }

        /* ── Net payable ── */
        .net-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background: #1a3a5c;
            border-top: 2px solid #1a1a18;
        }

        .net-label-block .net-eyebrow {
            font-family: 'DM Mono', monospace;
            font-size: 0.56rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #8aaac8;
            display: block;
            margin-bottom: 0.2rem;
        }

        .net-label-block .net-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .net-amount-block {
            font-family: 'DM Mono', monospace;
            font-size: 1.4rem;
            font-weight: 500;
            color: #ffffff;
            letter-spacing: -0.02em;
            font-feature-settings: "tnum";
        }

        .net-amount-block .taka {
            font-size: 1rem;
            opacity: 0.7;
            margin-right: 0.1rem;
        }

        /* ── Footer / Signatures ── */
        .slip-footer {
            padding: 1.5rem 1.25rem 1rem;
            border-top: 1px solid #e2e2dc;
            background: #fafaf8;
        }

        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 2.5rem;
            gap: 1rem;
        }

        .sig-block { flex: 1; text-align: center; }

        .sig-block { text-align: center; }

        .sig-line {
            width: 100%;
            border-top: 1px solid #9a9a92;
            margin: 0 auto 0.35rem auto;
        }

        .sig-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.56rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #9a9a92;
        }

        .footer-note {
            font-family: 'DM Mono', monospace;
            font-size: 0.58rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #b0b0a8;
            text-align: center;
            margin-top: 1.25rem;
        }

        /* ── Print ── */
        @media print {
            @page { margin: 12mm 15mm; size: A4; }

            body {
                background: #fff !important;
                padding: 0 !important;
                display: block !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

            .screen-only { display: none !important; }

            .slip {
                max-width: 100% !important;
                border: none !important;
                width: 100% !important;
            }

            .slip-header {
                padding: 1.2rem 1.5rem !important;
                border-bottom: 2px solid #1a1a18 !important;
            }

            .emp-row { -webkit-print-color-adjust: exact !important; }
            .emp-cell { padding: 0.7rem 1rem !important; }
            .ec-label { font-size: 0.52rem !important; color: #9a9a92 !important; }
            .ec-val { font-size: 0.78rem !important; }

            .section-label {
                background: #f8f8f5 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-size: 0.52rem !important;
                padding: 0.4rem 1rem !important;
            }

            .detail-table thead tr {
                background: #f8f8f5 !important;
                -webkit-print-color-adjust: exact !important;
            }
            .detail-table thead th { font-size: 0.56rem !important; padding: 0.45rem 1rem !important; }
            .detail-table tbody td { padding: 0.6rem 1rem !important; }
            .item-name { font-size: 0.75rem !important; }
            .item-note { font-size: 0.65rem !important; }
            .item-amount { font-size: 0.74rem !important; }
            .item-amount.earn { color: #1a5c3a !important; -webkit-print-color-adjust: exact !important; }

            .net-row {
                background: #1a3a5c !important;
                border-top: 2px solid #1a1a18 !important;
                padding: 0.85rem 1rem !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .net-eyebrow { color: #8aaac8 !important; font-size: 0.52rem !important; }
            .net-title { color: #ffffff !important; font-size: 0.85rem !important; }
            .net-amount-block { color: #ffffff !important; font-size: 1.25rem !important; }
            .taka { color: #ffffff !important; opacity: 0.7 !important; }

            .slip-footer {
                background: #fafaf8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                padding: 1rem 1rem 0.75rem !important;
            }
            .sig-line { border-top: 1px solid #9a9a92 !important; }
            .sig-label { font-size: 0.52rem !important; color: #9a9a92 !important; }
            .footer-note { font-size: 0.54rem !important; color: #b0b0a8 !important; }
        }
    </style>
</head>
<body>

{{-- Screen buttons --}}
<div class="screen-only">
    <button class="btn-print" onclick="window.print()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print / Save PDF
    </button>
    <a href="{{ url()->previous() }}" class="btn-back">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>

<div class="slip">

    {{-- Header --}}
    <div class="slip-header">
        <div class="company-block">
            <div class="company-name">Ashis Auto Solution</div>
            <div class="company-sub">Salary Disbursement</div>
        </div>
        <div class="slip-title-block">
            <span class="slip-label">Payment Slip</span>
            <div class="slip-month">{{ \Carbon\Carbon::parse($payment->payroll->month . '-01')->format('F Y') }}</div>
        </div>
    </div>

    {{-- Employee Info --}}
    <div class="emp-row">
        <div class="emp-cell">
            <span class="ec-label">Employee Name</span>
            <span class="ec-val">{{ $payment->employee->name }}</span>
        </div>
        <div class="emp-cell">
            <span class="ec-label">Payment Type</span>
            <span class="ec-val">
                <span class="badge-type">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</span>
            </span>
        </div>
        <div class="emp-cell">
            <span class="ec-label">Payment Date</span>
            <span class="ec-val">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</span>
        </div>
    </div>

    {{-- Payment Detail --}}
    <div class="section-label">Payment Detail</div>

    <table class="detail-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Remarks</th>
                <th style="text-align:right;">Amount (৳)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="item-name">Paid Amount</div>
                    <div class="item-note">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</div>
                </td>
                <td>
                    <span class="item-note">For {{ \Carbon\Carbon::parse($payment->payroll->month . '-01')->format('F Y') }}</span>
                </td>
                <td class="item-amount earn">{{ number_format($payment->paid_amount, 2) }}</td>
            </tr>

            {{-- Payroll reference row --}}
            <tr style="background:#fafaf8;">
                <td>
                    <div class="item-name" style="font-weight:700;">Gross Salary (Reference)</div>
                </td>
                <td><span class="item-note">Monthly base compensation</span></td>
                <td class="item-amount" style="font-weight:700;">{{ number_format($payment->payroll->employee->total_salary ?? 0, 2) }}</td>
            </tr>

            @if(($payment->payroll->absent_amount ?? 0) > 0)
            <tr style="background:#fff8f8;">
                <td>
                    <div class="item-name">Absent Deduction</div>
                    <div class="item-note">{{ $payment->payroll->absent_days ?? 0 }} day(s)</div>
                </td>
                <td><span class="item-note">Salary cut for absent</span></td>
                <td class="item-amount" style="color:#8a1a1a;">− {{ number_format($payment->payroll->absent_amount, 2) }}</td>
            </tr>
            @endif

            @if(($payment->payroll->loan_deduction ?? 0) > 0)
            <tr style="background:#fff8f8;">
                <td>
                    <div class="item-name">Loan Deduction</div>
                </td>
                <td><span class="item-note">Installment recovery</span></td>
                <td class="item-amount" style="color:#8a1a1a;">− {{ number_format($payment->payroll->loan_deduction, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- Net block --}}
    <div class="net-row">
        <div class="net-label-block">
            <span class="net-eyebrow">Total Amount Paid</span>
            <div class="net-title">Net Paid</div>
        </div>
        <div class="net-amount-block">
            <span class="taka">৳</span>{{ number_format($payment->paid_amount, 2) }}
        </div>
    </div>

    {{-- Footer / Signatures --}}
    <div class="slip-footer">
        <div class="sig-row">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Receiver</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Accounts</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Director</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Managing Director</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>