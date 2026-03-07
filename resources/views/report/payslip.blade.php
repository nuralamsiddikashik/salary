<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Slip — {{ $payroll->month }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Arial Narrow', Arial, sans-serif;
            background: #f0efe9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 1.5rem 1rem;
            color: #1a1a18;
            font-size: 11px;
        }

        /* Print button */
        .screen-only {
            margin-bottom: 1.25rem;
            display: flex;
            gap: 0.6rem;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1rem;
            background: #1a3a5c;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-family: 'Arial Narrow', Arial, sans-serif;
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
            padding: 0.45rem 1rem;
            background: #fff;
            color: #5a5a54;
            border: 1px solid #d0d0c8;
            border-radius: 5px;
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-back:hover { border-color: #1a3a5c; color: #1a3a5c; }

        /* Slip wrapper */
        .slip {
            width: 100%;
            max-width: 560px;
            background: #ffffff;
            border: 1px solid #d8d8d0;
        }

        /* ── Header ── */
        .slip-header {
            padding: 0.9rem 1.25rem;
            border-bottom: 2px solid #1a1a18;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .company-block .company-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a18;
            letter-spacing: 0.02em;
            line-height: 1;
            font-family: 'Arial Narrow', Arial, sans-serif;
        }

        .company-block .company-sub {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.58rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #9a9a92;
            margin-top: 0.25rem;
        }

        .slip-title-block { text-align: right; }

        .slip-title-block .slip-label {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.55rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #9a9a92;
            display: block;
            margin-bottom: 0.15rem;
        }

        .slip-title-block .slip-month {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1a1a18;
            font-family: 'Arial Narrow', Arial, sans-serif;
        }

        /* ── Employee Info ── */
        .emp-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid #e2e2dc;
        }

        .emp-cell {
            padding: 0.55rem 1rem;
            border-right: 1px solid #e2e2dc;
        }
        .emp-cell:last-child { border-right: none; }

        .emp-cell .ec-label {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.52rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #9a9a92;
            display: block;
            margin-bottom: 0.2rem;
        }

        .emp-cell .ec-val {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            color: #1a1a18;
        }

        /* ── Section label ── */
        .section-label {
            padding: 0.35rem 1rem;
            background: #f8f8f5;
            border-bottom: 1px solid #e2e2dc;
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.52rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #9a9a92;
        }

        /* ── Line items ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead tr {
            background: #f8f8f5;
            border-bottom: 1px solid #d0d0c8;
        }

        .items-table thead th {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.56rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #5a5a54;
            padding: 0.4rem 1rem;
            text-align: left;
        }
        .items-table thead th:last-child { text-align: right; }

        .items-table tbody tr {
            border-bottom: 1px solid #eeeeea;
        }
        .items-table tbody tr:last-child { border-bottom: none; }

        .items-table tbody td {
            padding: 0.45rem 1rem;
            vertical-align: middle;
        }

        .item-name {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.73rem;
            font-weight: 600;
            color: #1a1a18;
        }

        .item-note {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.62rem;
            color: #9a9a92;
            margin-top: 0.1rem;
        }

        .item-amount {
            text-align: right;
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.73rem;
            font-weight: 600;
            color: #1a1a18;
            font-feature-settings: "tnum";
            white-space: nowrap;
        }

        .item-amount.earn   { color: #1a5c3a; }
        .item-amount.deduct { color: #8a1a1a; }
        .item-amount.zero   { color: #c0c0b8; }

        /* ── Net payable ── */
        .net-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: transparent;
            border-top: 2px solid #1a1a18;
        }

        .net-label-block .net-eyebrow {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.52rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #9a9a92;
            display: block;
            margin-bottom: 0.15rem;
        }

        .net-label-block .net-title {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            color: #1a1a18;
        }

        .net-amount-block {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a1a18;
            letter-spacing: 0.01em;
            font-feature-settings: "tnum";
        }

        .net-amount-block .taka {
            font-size: 0.88rem;
            opacity: 0.6;
            margin-right: 0.1rem;
        }

        /* ── Footer ── */
        .slip-footer {
            padding: 1rem 1.25rem 0.75rem;
            border-top: 1px solid #e2e2dc;
            background: #fafaf8;
        }

        .sig-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .sig-block {
            flex: 1;
            text-align: center;
        }

        .sig-block .sig-line {
            border-top: 1px solid #9a9a92;
            margin-bottom: 0.3rem;
        }

        .sig-block .sig-label {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 0.55rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #9a9a92;
        }

        /* ── Print ── */
        @media print {
            @page { margin: 8mm 10mm; size: A4; }

            body {
                background: #fff !important;
                padding: 0 !important;
                display: block !important;
                font-family: 'Arial Narrow', Arial, sans-serif !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .screen-only { display: none !important; }

            .slip {
                max-width: 100% !important;
                border: 1px solid #d8d8d0 !important;
                box-shadow: none !important;
                width: 100% !important;
            }

            /* Header */
            .slip-header {
                padding: 0.75rem 1rem !important;
                border-bottom: 2px solid #1a1a18 !important;
                background: #fff !important;
                -webkit-print-color-adjust: exact !important;
            }

            .company-block .company-name {
                font-size: 0.88rem !important;
                font-weight: 700 !important;
                color: #1a1a18 !important;
            }

            /* Employee row */
            .emp-row {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .emp-cell {
                padding: 0.45rem 0.85rem !important;
            }
            .emp-cell .ec-label {
                font-size: 0.48rem !important;
                color: #9a9a92 !important;
            }
            .emp-cell .ec-val {
                font-size: 0.7rem !important;
                color: #1a1a18 !important;
            }

            /* Section label */
            .section-label {
                background: #f8f8f5 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-size: 0.48rem !important;
                padding: 0.3rem 0.85rem !important;
            }

            /* Table */
            .items-table thead tr {
                background: #f8f8f5 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .items-table thead th {
                font-size: 0.52rem !important;
                padding: 0.35rem 0.85rem !important;
                color: #5a5a54 !important;
            }
            .items-table tbody td {
                padding: 0.4rem 0.85rem !important;
            }
            .item-name { font-size: 0.68rem !important; color: #1a1a18 !important; }
            .item-note { font-size: 0.58rem !important; color: #9a9a92 !important; }
            .item-amount { font-size: 0.68rem !important; }
            .item-amount.earn   { color: #1a5c3a !important; -webkit-print-color-adjust: exact !important; }
            .item-amount.deduct { color: #8a1a1a !important; -webkit-print-color-adjust: exact !important; }
            .item-amount.zero   { color: #c0c0b8 !important; }

            /* Gross subtotal row */
            .items-table tbody tr[style*="fafaf8"] {
                background: #fafaf8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Deduction rows */
            .items-table tbody tr[style*="fff8f8"] {
                background: #fff8f8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Net payable */
            .net-row {
                background: transparent !important;
                border-top: 2px solid #1a1a18 !important;
                padding: 0.65rem 0.85rem !important;
            }
            .net-label-block .net-eyebrow {
                color: #9a9a92 !important;
                font-size: 0.48rem !important;
            }
            .net-label-block .net-title {
                color: #1a1a18 !important;
                font-size: 0.78rem !important;
            }
            .net-amount-block {
                color: #1a1a18 !important;
                font-size: 1.05rem !important;
            }
            .net-amount-block .taka {
                color: #1a1a18 !important;
                opacity: 0.6 !important;
            }

            /* Footer */
            .slip-footer {
                background: #fafaf8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                padding: 0.85rem 1rem 0.65rem !important;
            }
            .sig-block .sig-line { border-top: 1px solid #9a9a92 !important; }
            .sig-block .sig-label { font-size: 0.48rem !important; color: #9a9a92 !important; }
        }
    </style>
</head>
<body>

<div class="screen-only">
    <button class="btn-print" onclick="window.print()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
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
            <div class="company-name">Company Name</div>
            <div class="company-sub">Salary Disbursement</div>
        </div>
        <div class="slip-title-block">
            <span class="slip-label">Pay Slip</span>
            <div class="slip-month">{{ \Carbon\Carbon::parse($payroll->month . '-01')->format('F Y') }}</div>
        </div>
    </div>

    {{-- Employee Info --}}
    <div class="emp-row">
        <div class="emp-cell">
            <span class="ec-label">Employee Name</span>
            <span class="ec-val">{{ $payroll->employee->name }}</span>
        </div>
        <div class="emp-cell">
            <span class="ec-label">Designation</span>
            <span class="ec-val">{{ $payroll->employee->designation }}</span>
        </div>
        <div class="emp-cell">
            <span class="ec-label">Pay Period</span>
            <span class="ec-val">{{ \Carbon\Carbon::parse($payroll->month . '-01')->format('M Y') }}</span>
        </div>
    </div>

    {{-- Salary Breakdown --}}
    <div class="section-label">Salary Breakdown</div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Remarks</th>
                <th style="text-align:right;">Amount (৳)</th>
            </tr>
        </thead>
        <tbody>

            {{-- Earnings --}}
            <tr>
                <td>
                    <div class="item-name">Basic Salary</div>
                </td>
                <td><span class="item-note">—</span></td>
                <td class="item-amount earn">{{ number_format($payroll->employee->basic_salary, 2) }}</td>
            </tr>
            <tr>
                <td><div class="item-name">House Rent</div></td>
                <td><span class="item-note">—</span></td>
                <td class="item-amount earn">{{ number_format($payroll->employee->house_rent, 2) }}</td>
            </tr>
            <tr>
                <td><div class="item-name">Medical Allowance</div></td>
                <td><span class="item-note">—</span></td>
                <td class="item-amount earn">{{ number_format($payroll->employee->medical, 2) }}</td>
            </tr>
            <tr>
                <td><div class="item-name">Conveyance</div></td>
                <td><span class="item-note">—</span></td>
                <td class="item-amount earn">{{ number_format($payroll->employee->conveyance, 2) }}</td>
            </tr>

            {{-- Subtotal --}}
            <tr style="background:#fafaf8; border-top:1px solid #d0d0c8;">
                <td><div class="item-name" style="font-weight:700;">Gross Salary</div></td>
                <td></td>
                <td class="item-amount" style="font-weight:700;">{{ number_format($payroll->employee->total_salary, 2) }}</td>
            </tr>

            {{-- Deductions --}}
            <tr style="background:#fff8f8;">
                <td>
                    <div class="item-name">Absent Deduction</div>
                    <div class="item-note">
                        {{ $payroll->absent_days }} absent
                        @if(($payroll->leave_used ?? 0) > 0)
                            · {{ $payroll->leave_used }} leave applied
                            · {{ $payroll->salary_cut_days ?? $payroll->absent_days }} cut
                        @endif
                    </div>
                </td>
                <td>
                    @if(($payroll->absent_days ?? 0) > 0)
                        <span class="item-note">
                            {{ $payroll->absent_days }} day(s) @if(($payroll->leave_used ?? 0) > 0) − {{ $payroll->leave_used }} leave @endif
                        </span>
                    @else
                        <span class="item-note">—</span>
                    @endif
                </td>
                <td class="item-amount {{ ($payroll->absent_amount ?? 0) > 0 ? 'deduct' : 'zero' }}">
                    @if(($payroll->absent_amount ?? 0) > 0)
                        − {{ number_format($payroll->absent_amount, 2) }}
                    @else
                        —
                    @endif
                </td>
            </tr>

            <tr style="background:#fff8f8;">
                <td>
                    <div class="item-name">Loan Deduction</div>
                </td>
                <td>
                    <span class="item-note">
                        @if(($payroll->loan_deduction ?? 0) > 0) Installment recovery
                        @else No active loan
                        @endif
                    </span>
                </td>
                <td class="item-amount {{ ($payroll->loan_deduction ?? 0) > 0 ? 'deduct' : 'zero' }}">
                    @if(($payroll->loan_deduction ?? 0) > 0)
                        − {{ number_format($payroll->loan_deduction, 2) }}
                    @else
                        —
                    @endif
                </td>
            </tr>

            <tr style="background:#fff8f8;">
                <td>
                    <div class="item-name">Advance Salary Deduction</div>
                </td>
                <td>
                    <span class="item-note">
                        @if(($advanceAmount ?? 0) > 0) Advance recovered
                        @else No advance taken
                        @endif
                    </span>
                </td>
                <td class="item-amount {{ ($advanceAmount ?? 0) > 0 ? 'deduct' : 'zero' }}">
                    @if(($advanceAmount ?? 0) > 0)
                        − {{ number_format($advanceAmount, 2) }}
                    @else
                        —
                    @endif
                </td>
            </tr>

        </tbody>
    </table>

    {{-- Net Payable --}}
    <div class="net-row">
        <div class="net-label-block">
            <span class="net-eyebrow">Total Amount Due</span>
            <div class="net-title">Net Payable</div>
        </div>
        <div class="net-amount-block">
            <span class="taka">৳</span>{{ number_format($payroll->net_payable, 2) }}
        </div>
    </div>

    {{-- Footer / Signatures --}}
    <div class="slip-footer">
        <div class="sig-row">
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