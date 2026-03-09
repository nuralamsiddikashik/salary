<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payroll Report — {{ $month }}</title>
    <style>
        @page {
            margin: 12pt 20pt 6pt 20pt;
            size: A4 landscape;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #000;
            background: #fff;
            padding: 0 18pt;
        }

        /* ── Page wrapper ── */
        .page {
            width: 100%;
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: auto;
        }

        /* ── First page header ── */
        .page-header {
            width: 100%;
            border-bottom: 2pt solid #000;
            padding-bottom: 4pt;
            margin-bottom: 5pt;
        }
        .page-header table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .page-header table td {
            border: none;
            padding: 0;
            vertical-align: bottom;
            background: transparent;
        }
        .page-header h1 { font-size: 12pt; font-weight: bold; margin-bottom: 1pt; }
        .page-header .sub { font-size: 7pt; color: #444; }
        .h-right { text-align: right; font-size: 6.5pt; color: #555; width: 30%; }

        /* ── Subsequent page mini-header ── */
        .page-subsequent { padding-top: 3pt; }

        .mini-header {
            width: 100%;
            border-bottom: 1pt solid #bbb;
            margin-bottom: 4pt;
            padding-bottom: 2pt;
        }
        .mini-header table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .mini-header table td {
            border: none;
            padding: 0;
            background: transparent;
            font-size: 6.5pt;
            color: #555;
            vertical-align: bottom;
        }

        /* ── Report table ── */
        .rpt-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7.2pt;
        }

        .group-emp {
            background-color: #dbeafe;
            color: #1e3a8a;
            font-weight: bold;
            text-align: center;
            padding: 3pt 2pt;
            border: 1px solid #999;
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .group-pay {
            background-color: #fef9c3;
            color: #7c4a00;
            font-weight: bold;
            text-align: center;
            padding: 3pt 2pt;
            border: 1px solid #999;
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        th.col {
            background-color: #f0f0f0;
            font-size: 6.5pt;
            font-weight: bold;
            text-align: center;
            padding: 3.5pt 2pt;
            border: 1px solid #aaa;
        }
        th.col-deduct {
            background-color: #fff0f0;
            color: #8a1a1a;
            font-size: 6.5pt;
            font-weight: bold;
            text-align: center;
            padding: 3.5pt 2pt;
            border: 1px solid #e8c5c5;
        }

        td {
            padding: 3.5pt 2.5pt;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
            word-break: break-word;
            line-height: 1.25;
        }

        .td-name   { font-weight: bold; font-size: 7.5pt; text-align: left; }
        .td-bold   { font-weight: bold; text-align: right; font-size: 7.2pt; }
        .td-money  { text-align: right; font-size: 7.2pt; }
        .td-center { text-align: center; font-size: 7.2pt; }
        .td-sign   { height: 18pt; }
        .td-deduct {
            text-align: right;
            font-size: 7.2pt;
            font-weight: bold;
            color: #8a1a1a;
            background-color: #fff8f8 !important;
        }

        .row-even td { background-color: #f9f9f9; }
        .row-odd  td { background-color: #ffffff; }

        /* ── Summary ── */
        .summary {
            margin-top: 5pt;
            border-top: 1pt solid #ccc;
            padding-top: 4pt;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .summary-table td {
            border: 1px solid #ddd;
            background: #f9f9f9;
            padding: 3pt 5pt;
            text-align: left;
            width: 20%;
        }
        .summary-table td.s-red { background: #fff8f8; border-color: #e8c5c5; }
        .summary-label { font-size: 5pt; color: #777; display: block; margin-bottom: 1pt; text-transform: uppercase; letter-spacing: 0.05em; }
        .summary-value { font-size: 8pt; font-weight: bold; }
        .summary-value.v-red { color: #8a1a1a; }

        /* ── Signatures ── */
        .sig-wrapper { margin-top: 8pt; }
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .sig-table td {
            border: none;
            text-align: center;
            vertical-align: bottom;
            background: transparent;
            padding: 0;
            width: 33.33%;
            padding-top: 36pt;
        }
        .sig-line {
            border-top: 1pt solid #000;
            width: 60%;
            margin: 0 auto 2pt auto;
        }
        .sig-text { font-size: 6pt; font-weight: bold; text-transform: uppercase; }

        /* ── Page number ── */
        .page-num {
            text-align: right;
            font-size: 6pt;
            color: #aaa;
            margin-top: 3pt;
        }
    </style>
</head>
<body>

@php
    $perPage     = 10;
    $chunks      = $employees->chunk($perPage);
    $totalChunks = $chunks->count();
    $totalSalary = $employees->sum('total_salary');
    $totalNet    = $employees->sum(fn($e) => (float)($e->payrolls->first()->net_payable ?? $e->total_salary));
    $totalAdv    = $employees->sum(fn($e) => $e->advances->sum('amount'));
    $totalDeduct = $employees->sum(fn($e) =>
        ($e->payrolls->first()->absent_amount     ?? 0) +
        ($e->payrolls->first()->advance_deduction ?? 0) +
        ($e->payrolls->first()->loan_deduction    ?? 0)
    );
    $globalSerial = 1;
@endphp

@foreach($chunks as $pageIndex => $chunk)

<div class="page {{ $pageIndex > 0 ? 'page-subsequent' : '' }}">

    {{-- First page: full header --}}
    @if($pageIndex === 0)
    <div class="page-header">
        <table>
            <tr>
                <td style="width:70%; vertical-align:bottom;">
                    <h1>Ashis Auto Solution</h1>
                    <div class="sub">Full Payroll Report &nbsp;&middot;&nbsp; Period: <strong>{{ $month }}</strong> &nbsp;&middot;&nbsp; {{ $employees->count() }} Employee(s)</div>
                </td>
                <td class="h-right">
                    Generated: {{ now()->format('d M Y, h:i A') }}<br>
                    Page {{ $pageIndex + 1 }} of {{ $totalChunks }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Subsequent pages: slim mini-header --}}
    @else
    <div class="mini-header">
        <table>
            <tr>
                <td style="text-align:left; font-weight:bold; width:70%;">
                    Ashis Auto Solution &mdash; Payroll Report &nbsp;&middot;&nbsp; {{ $month }}
                </td>
                <td style="text-align:right; width:30%;">
                    Page {{ $pageIndex + 1 }} of {{ $totalChunks }}
                </td>
            </tr>
        </table>
    </div>
    @endif

    {{-- Report table --}}
    <table class="rpt-table">
        <colgroup>
            <col style="width:2.5%">  {{-- SL --}}
            <col style="width:9.5%">  {{-- Name --}}
            <col style="width:5.5%">  {{-- Join Date --}}
            <col style="width:7.5%">  {{-- Desig. --}}
            <col style="width:5%">    {{-- Total --}}
            <col style="width:5%">    {{-- Basic --}}
            <col style="width:4.5%">  {{-- House --}}
            <col style="width:4.5%">  {{-- Med. --}}
            <col style="width:4.5%">  {{-- Conv. --}}
            <col style="width:4.5%">  {{-- Month --}}
            <col style="width:3.5%">  {{-- Abs. --}}
            <col style="width:3.5%">  {{-- Leave --}}
            <col style="width:3.5%">  {{-- Cut --}}
            <col style="width:5%">    {{-- Abs.Amt --}}
            <col style="width:5%">    {{-- Loan --}}
            <col style="width:5%">    {{-- Advance --}}
            <col style="width:5.5%">  {{-- Tot.Deduct --}}
            <col style="width:4%">    {{-- Rem.Lv --}}
            <col style="width:6%">    {{-- Net Pay --}}
            <col style="width:5%">    {{-- Sign --}}
        </colgroup>
        <thead>
            <tr>
                <th colspan="9"  class="group-emp">Employee Info</th>
                <th colspan="11" class="group-pay">Payroll &mdash; {{ $month }}</th>
            </tr>
            <tr>
                <th class="col">SL</th>
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
                <th class="col">Leave</th>
                <th class="col">Cut</th>
                <th class="col">Abs.Amt</th>
                <th class="col">Loan</th>
                <th class="col">Advance</th>
                <th class="col-deduct">Tot.Deduct</th>
                <th class="col">Rem.Lv</th>
                <th class="col">Net Pay</th>
                <th class="col">Sign</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chunk as $i => $employee)
            @php
                $payroll      = $employee->payrolls->first();
                $absent       = $payroll->absent_days ?? 0;
                $leave        = $payroll->leave_used ?? 0;
                $cutDays      = $payroll->salary_cut_days ?? 0;
                $advAmt       = $employee->advances->sum('amount');
                $rowDeduction = ($payroll->absent_amount     ?? 0)
                              + ($payroll->advance_deduction ?? 0)
                              + ($payroll->loan_deduction    ?? 0);
                $rowClass     = $i % 2 === 0 ? 'row-even' : 'row-odd';
                $sl           = $globalSerial;
                $globalSerial++;
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="td-center">{{ $sl }}</td>
                <td class="td-name">{{ $employee->name }}</td>
                <td class="td-center">{{ \Carbon\Carbon::parse($employee->join_date)->format('d/m/y') }}</td>
                <td class="td-center">{{ $employee->designation }}</td>
                <td class="td-bold">{{ number_format($employee->total_salary, 0) }}</td>
                <td class="td-money">{{ number_format($employee->basic_salary, 0) }}</td>
                <td class="td-money">{{ number_format($employee->house_rent, 0) }}</td>
                <td class="td-money">{{ number_format($employee->medical, 0) }}</td>
                <td class="td-money">{{ number_format($employee->conveyance, 0) }}</td>
                <td class="td-center">{{ $payroll ? $payroll->month : '—' }}</td>
                <td class="td-center">{{ $absent > 0 ? $absent : '—' }}</td>
                <td class="td-center">{{ $leave > 0 ? $leave : '—' }}</td>
                <td class="td-center">{{ $cutDays > 0 ? $cutDays : '—' }}</td>
                <td class="td-money">{{ ($payroll->absent_amount ?? 0) > 0 ? number_format($payroll->absent_amount, 0) : '—' }}</td>
                <td class="td-money">{{ ($payroll->loan_deduction ?? 0) > 0 ? number_format($payroll->loan_deduction, 0) : '—' }}</td>
                <td class="td-money">{{ $advAmt > 0 ? number_format($advAmt, 0) : '—' }}</td>
                <td class="td-deduct">{{ $rowDeduction > 0 ? number_format($rowDeduction, 0) : '—' }}</td>
                <td class="td-center">{{ $employee->remaining_leave }}</td>
                <td class="td-bold">{{ number_format($payroll->net_payable ?? $employee->total_salary, 2) }}</td>
                <td class="td-sign"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary — last page only --}}
    @if($pageIndex === $totalChunks - 1)
    <div class="summary">
        <table class="summary-table">
            <tr>
                <td>
                    <span class="summary-label">Total Employees</span>
                    <span class="summary-value">{{ $employees->count() }}</span>
                </td>
                <td>
                    <span class="summary-label">Total Salary</span>
                    <span class="summary-value">{{ number_format($totalSalary, 0) }}</span>
                </td>
                <td class="s-red">
                    <span class="summary-label">Advance Total</span>
                    <span class="summary-value v-red">{{ number_format($totalAdv, 0) }}</span>
                </td>
                <td class="s-red">
                    <span class="summary-label">Total Deduction</span>
                    <span class="summary-value v-red">{{ number_format($totalDeduct, 0) }}</span>
                </td>
                <td>
                    <span class="summary-label">Net Payable</span>
                    <span class="summary-value">{{ number_format($totalNet, 0) }}</span>
                </td>
            </tr>
        </table>
    </div>
    @endif

    {{-- Signatures — every page --}}
    <div class="sig-wrapper">
        <table class="sig-table">
            <tr>
                <td><div class="sig-line"></div><div class="sig-text">Accounts</div></td>
                <td><div class="sig-line"></div><div class="sig-text">Director</div></td>
                <td><div class="sig-line"></div><div class="sig-text">Managing Director</div></td>
            </tr>
        </table>
    </div>

    <div class="page-num">Page {{ $pageIndex + 1 }} / {{ $totalChunks }}</div>

</div>

@endforeach

</body>
</html>