<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payroll Report — {{ $month }}</title>
    <style>
        /* ── Page Margins for Print ── */
        @page {
            margin: 20pt 30pt;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7.5pt;
            color: #000;
            background: #fff;
            padding: 0 5pt;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000;
            padding-bottom: 6pt;
            margin-bottom: 8pt;
        }

        .page-header h1 {
            font-size: 14pt;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 6.2pt;
        }

        .page-wrapper {
            margin-top: 20pt;
        }

        .group-emp { background-color: #dbeafe; color: #1e3a8a; font-weight: bold; text-align: center; padding: 3pt; border: 1px solid #999; }
        .group-pay { background-color: #fef9c3; color: #7c4a00; font-weight: bold; text-align: center; padding: 3pt; border: 1px solid #999; }

        th.col {
            background-color: #f0f0f0;
            font-size: 5.8pt;
            font-weight: bold;
            text-align: center;
            padding: 4pt 1pt;
            border: 1px solid #aaa;
        }

        td {
            padding: 4pt 2pt;
            border: 1px solid #e2e2e2;
            vertical-align: middle;
            word-break: break-word;
            line-height: 1.2;
        }

        tr:nth-child(even) td { background-color: #f9f9f9; }

        .td-name { font-weight: bold; font-size: 6.5pt; text-align: left; }
        .td-bold { font-weight: bold; text-align: right; }
        .td-money { text-align: right; }
        .td-sign-box { height: 18pt; }

        /* Signature Section */
        .signature-wrapper {
            margin-top: 30pt;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border: none !important;
        }

        .signature-table td {
            border: none !important;
            text-align: center;
            vertical-align: bottom;
            background: transparent !important;
            width: 25%;
        }

        .sig-line {
            border-top: 1px solid #000 !important;
            width: 80%;
            margin: 0 auto 3pt auto;
        }

        .sig-text {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .page-break { 
            page-break-after: always; 
        }

        .page-num { text-align: right; font-size: 6pt; color: #aaa; margin-top: 6pt; }

        .summary { margin-top: 10pt; border-top: 1.5px solid #ccc; padding-top: 8pt; }
        .summary-grid { display: table; width: 100%; border-spacing: 3pt; }
        .summary-item { display: table-cell; width: 25%; border: 1px solid #ddd; background: #f9f9f9; padding: 5pt 8pt; }
        .summary-label { font-size: 5.5pt; color: #666; display: block; }
        .summary-value { font-size: 9pt; font-weight: bold; }
    </style>
</head>
<body>

@php
    $chunks      = $employees->chunk(15);
    $totalChunks = $chunks->count();
    $totalSalary = $employees->sum('total_salary');
    $totalNet    = $employees->sum(fn($e) => $e->payrolls->first()->net_payable ?? $e->total_salary);
    $totalLoan   = $employees->sum(fn($e) => $e->payrolls->first()->loan_deduction ?? 0);
@endphp

@foreach($chunks as $pageIndex => $chunk)

    <div class="{{ $pageIndex > 0 ? 'page-wrapper' : '' }}">

        @if($pageIndex === 0)
        <div class="page-header">
            <div>
                <h1>Full Payroll Report</h1>
                <div class="meta" style="font-size: 7pt; color: #444;">
                    Period: <strong>{{ $month }}</strong> &nbsp;·&nbsp; {{ $employees->count() }} Employee(s)
                </div>
            </div>
            <div style="text-align: right; font-size: 7pt; color: #444;">
                Printed: {{ now()->format('d M Y, h:i A') }}<br>
                Page: {{ $pageIndex + 1 }} / {{ $totalChunks }}
            </div>
        </div>
        @endif

        <table>
            <colgroup>
                <col style="width:10%"><col style="width:7%"><col style="width:9%"><col style="width:7%"><col style="width:6%">
                <col style="width:6%"><col style="width:5%"><col style="width:5%"><col style="width:6%"><col style="width:4%">
                <col style="width:7%"><col style="width:7%"><col style="width:8%"><col style="width:13%">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="8" class="group-emp">Employee Info</th>
                    <th colspan="6" class="group-pay">Payroll — {{ $month }}</th>
                </tr>
                <tr>
                    <th class="col">Name</th>
                    <th class="col">Join Date</th>
                    <th class="col">Desig.</th>
                    <th class="col">Total</th>
                    <th class="col">Basic</th>
                    <th class="col">House</th>
                    <th class="col">Med.</th>
                    <th class="col">Conv.</th>
                    <th class="col">Month</th>
                    <th class="col">Abs.</th>
                    <th class="col">Abs. Amt</th>
                    <th class="col">Loan</th>
                    <th class="col">Net Pay</th>
                    <th class="col">Sign</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunk as $employee)
                @php
                    $payroll = $employee->payrolls->first();
                    $absent  = $payroll->absent_days ?? 0;
                @endphp
                <tr>
                    <td class="td-name">{{ $employee->name }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($employee->join_date)->format('d/m/y') }}</td>
                    <td>{{ $employee->designation }}</td>
                    <td class="td-bold">{{ number_format($employee->total_salary, 0) }}</td>
                    <td class="td-money">{{ number_format($employee->basic_salary, 0) }}</td>
                    <td class="td-money">{{ number_format($employee->house_rent, 0) }}</td>
                    <td class="td-money">{{ number_format($employee->medical, 0) }}</td>
                    <td class="td-money">{{ number_format($employee->conveyance, 0) }}</td>
                    <td style="text-align: center;">{{ $payroll->month ?? '—' }}</td>
                    <td style="text-align: center;">{{ $absent > 0 ? $absent : '—' }}</td>
                    <td class="td-money">{{ number_format($payroll->absent_amount ?? 0, 0) }}</td>
                    <td class="td-money">{{ number_format($payroll->loan_deduction ?? 0, 0) }}</td>
                    <td class="td-bold">{{ number_format($payroll->net_payable ?? $employee->total_salary, 0) }}</td>
                    <td class="td-sign-box"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($pageIndex === $totalChunks - 1)
        <div class="summary">
            <div class="summary-grid">
                <div class="summary-item"><span class="summary-label">Total Employees</span><span class="summary-value">{{ $employees->count() }}</span></div>
                <div class="summary-item"><span class="summary-label">Total Salary</span><span class="summary-value">{{ number_format($totalSalary, 0) }}</span></div>
                <div class="summary-item"><span class="summary-label">Loan Deductions</span><span class="summary-value">{{ number_format($totalLoan, 0) }}</span></div>
                <div class="summary-item"><span class="summary-label">Net Payable</span><span class="summary-value">{{ number_format($totalNet, 0) }}</span></div>
            </div>
        </div>
        @endif

        <div class="signature-wrapper">
            <table class="signature-table">
                <tr>
                    <td><div class="sig-line"></div><div class="sig-text">Created By</div></td>
                    <td><div class="sig-line"></div><div class="sig-text">Accounts</div></td>
                    <td><div class="sig-line"></div><div class="sig-text">Director</div></td>
                    <td><div class="sig-line"></div><div class="sig-text">Managing Director</div></td>
                </tr>
            </table>
        </div>

        <div class="page-num">Page {{ $pageIndex + 1 }} / {{ $totalChunks }}</div>

    </div>

    @if($pageIndex < $totalChunks - 1)
        <div class="page-break"></div>
    @endif

@endforeach

</body>
</html>