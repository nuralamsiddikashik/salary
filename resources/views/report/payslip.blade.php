<!DOCTYPE html>
<html>
<head>
    <title>Pay Slip - {{ $payroll->month }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink: #1a1410;
            --ink-soft: #5a4e44;
            --paper: #faf7f2;
            --cream: #f0ebe0;
            --gold: #b8954a;
            --gold-light: #d4b06a;
            --rule: #d5c9b8;
            --red: #c0392b;
        }

        body {
            background: #e8e2d8;
            background-image:
                repeating-linear-gradient(0deg, transparent, transparent 28px, rgba(0,0,0,0.03) 28px, rgba(0,0,0,0.03) 29px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            font-family: 'DM Sans', sans-serif;
        }

        .print-btn {
            display: block;
            margin: 0 auto 20px;
            background: var(--ink);
            color: var(--paper);
            border: none;
            padding: 10px 28px;
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }
        .print-btn:hover { background: var(--gold); }

        /* VOUCHER WRAPPER */
        .voucher {
            max-width: 720px;
            width: 100%;
            background: var(--paper);
            box-shadow: 0 20px 60px rgba(0,0,0,0.18), 0 4px 12px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        /* Perforation top & bottom */
        .voucher::before, .voucher::after {
            content: '';
            display: block;
            height: 14px;
            background:
                radial-gradient(circle at 7px 7px, #e8e2d8 7px, transparent 7px),
                var(--gold);
            background-size: 22px 14px;
            background-position: 0 0;
        }
        .voucher::after {
            background:
                radial-gradient(circle at 7px 7px, #e8e2d8 7px, transparent 7px),
                var(--gold);
            background-size: 22px 14px;
        }

        /* Gold top bar */
        .voucher-header {
            background: var(--ink);
            padding: 28px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .voucher-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
        }

        .company-mark {
            display: flex;
            flex-direction: column;
        }

        .company-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: var(--paper);
            letter-spacing: 1px;
        }

        .company-sub {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--gold-light);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .payslip-badge {
            text-align: right;
        }

        .payslip-badge .label {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--gold);
            display: block;
        }

        .payslip-badge .month-val {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--paper);
        }

        /* Body */
        .voucher-body {
            padding: 36px 44px;
        }

        /* Employee info strip */
        .emp-strip {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0;
            border: 1px solid var(--rule);
            margin-bottom: 32px;
        }

        .emp-field {
            padding: 14px 18px;
            border-right: 1px solid var(--rule);
        }
        .emp-field:last-child { border-right: none; }

        .emp-field .f-label {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--gold);
            display: block;
            margin-bottom: 5px;
        }

        .emp-field .f-val {
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .divider span {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            white-space: nowrap;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--rule);
        }

        /* Line items */
        .line-items { width: 100%; border-collapse: collapse; }

        .line-items thead tr {
            border-bottom: 2px solid var(--ink);
        }

        .line-items thead th {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--ink-soft);
            padding: 8px 0 10px;
            font-weight: 500;
            text-align: left;
        }
        .line-items thead th:last-child { text-align: right; }

        .line-items tbody tr {
            border-bottom: 1px dashed var(--rule);
        }

        .line-items tbody td {
            padding: 14px 0;
            font-size: 14px;
            color: var(--ink);
        }

        .line-items tbody td:last-child {
            text-align: right;
            font-family: 'DM Mono', monospace;
            font-size: 13px;
        }

        .line-items .desc-main { font-weight: 500; }
        .line-items .desc-sub {
            font-size: 11px;
            color: var(--ink-soft);
            margin-top: 2px;
        }

        .deduction { color: var(--red) !important; }

        /* Net payable block */
        .net-block {
            margin-top: 28px;
            background: var(--ink);
            padding: 20px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .net-block::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
        }

        .net-label {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--paper);
        }
        .net-label small {
            display: block;
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 2px;
            color: var(--gold-light);
            font-style: normal;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .net-amount {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: var(--gold-light);
            letter-spacing: 1px;
        }

        /* Footer strip */
        .voucher-footer {
            padding: 16px 44px;
            background: var(--cream);
            border-top: 1px solid var(--rule);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-note {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 1.5px;
            color: var(--ink-soft);
            text-transform: uppercase;
        }

        .stamp {
            width: 52px; height: 52px;
            border: 2px solid var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Mono', monospace;
            font-size: 8px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gold);
            text-align: center;
            line-height: 1.3;
            transform: rotate(-12deg);
            opacity: 0.7;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-family: 'Playfair Display', serif;
            font-size: 80px;
            color: rgba(184, 149, 74, 0.04);
            white-space: nowrap;
            pointer-events: none;
            user-select: none;
            z-index: 0;
        }

        .voucher-body { position: relative; z-index: 1; }

        @media print {
            body { background: white; padding: 0; }
            .print-btn { display: none; }
            .voucher { box-shadow: none; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">⎙ Print Voucher</button>

<div class="voucher">

    <div class="voucher-header">
        <div class="company-mark">
            <span class="company-name">Company Name</span>
            <span class="company-sub">Human Resources</span>
        </div>
        <div class="payslip-badge">
            <span class="label">Pay Period</span>
            <span class="month-val">{{ $payroll->month }}</span>
        </div>
    </div>

    <div class="voucher-body">
        <div class="watermark">PAYSLIP</div>

        <div class="emp-strip">
            <div class="emp-field">
                <span class="f-label">Employee</span>
                <span class="f-val">{{ $payroll->employee->name }}</span>
            </div>
            <div class="emp-field">
                <span class="f-label">Designation</span>
                <span class="f-val">{{ $payroll->employee->designation }}</span>
            </div>
            <div class="emp-field">
                <span class="f-label">Absent Days</span>
                <span class="f-val">{{ $payroll->absent_days }} days</span>
            </div>
        </div>

        <div class="divider"><span>Earnings & Deductions</span></div>

        <table class="line-items">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="desc-main">Gross Salary</div>
                        <div class="desc-sub">Monthly base compensation</div>
                    </td>
                    <td>{{ $payroll->employee->total_salary }}</td>
                </tr>
                <tr>
                    <td>
                        <div class="desc-main">Absent Deduction</div>
                        <div class="desc-sub">{{ $payroll->absent_days }} day(s) absent</div>
                    </td>
                    <td class="deduction">− {{ $payroll->absent_amount }}</td>
                </tr>
                <tr>
                    <td>
                        <div class="desc-main">Loan Deduction</div>
                        <div class="desc-sub">Installment recovery</div>
                    </td>
                    <td class="deduction">− {{ $payroll->loan_deduction }}</td>
                </tr>
            </tbody>
        </table>

        <div class="net-block">
            <div class="net-label">
                <small>Net Payable</small>
                Total Amount Due
            </div>
            <div class="net-amount">{{ $payroll->net_payable }}</div>
        </div>

    </div>

    <div class="voucher-footer">
        <span class="footer-note">This is a computer-generated voucher</span>
        <div class="stamp">Paid<br>✓</div>
        <span class="footer-note">{{ $payroll->month }}</span>
    </div>

</div>

</body>
</html>