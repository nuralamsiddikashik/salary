<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Salary Payment Report — {{ $month }}</title>
    <style>
        @page { margin: 20pt 25pt; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
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

        .page-header h1 { font-size: 14pt; font-weight: bold; }

        .company-sub {
            font-size: 7pt;
            color: #555;
            margin-top: 2pt;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        /* subsequent pages top padding */
        .page-wrapper { margin-top: 22pt; }

        /* sub-header for page 2+ */
        .sub-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4pt;
            margin-bottom: 8pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7.5pt;
        }

        .group-emp {
            background-color: #dbeafe;
            color: #1e3a8a;
            font-weight: bold;
            text-align: center;
            padding: 3pt;
            border: 1px solid #999;
        }

        .group-pay {
            background-color: #fef9c3;
            color: #7c4a00;
            font-weight: bold;
            text-align: center;
            padding: 3pt;
            border: 1px solid #999;
        }

        th.col {
            background-color: #f0f0f0;
            font-size: 7pt;
            font-weight: bold;
            text-align: center;
            padding: 4pt 2pt;
            border: 1px solid #aaa;
        }

        td {
            padding: 4pt 3pt;
            border: 1px solid #e2e2e2;
            vertical-align: middle;
            word-break: break-word;
            line-height: 1.2;
        }

        tr:nth-child(even) td { background-color: #f9f9f9; }

        .td-sl     { text-align: center; color: #999; font-size: 7pt; }
        .td-name   { font-weight: bold; font-size: 7.8pt; text-align: left; }
        .td-des    { font-size: 7pt; text-align: left; }
        .td-bold   { font-weight: bold; text-align: right; font-size: 7.8pt; }
        .td-green  { font-weight: bold; text-align: right; font-size: 7.8pt; }
        .td-center { text-align: center; }

        /* summary */
        .summary { margin-top: 10pt; border-top: 1.5px solid #ccc; padding-top: 8pt; }
        .summary-grid { display: table; width: 100%; border-spacing: 3pt; }
        .summary-item { display: table-cell; width: 25%; border: 1px solid #ddd; background: #f9f9f9; padding: 5pt 8pt; }
        .summary-label { font-size: 6pt; color: #666; display: block; }
        .summary-value { font-size: 10pt; font-weight: bold; }

        /* signatures */
        .signature-wrapper { margin-top: 30pt; width: 100%; }
        .signature-table { width: 100%; border: none !important; }
        .signature-table td {
            border: none !important;
            text-align: center;
            vertical-align: bottom;
            background: transparent !important;
            width: 25%;
        }
        .sig-line { border-top: 1px solid #000 !important; width: 80%; margin: 0 auto 3pt auto; }
        .sig-text { font-size: 7pt; font-weight: bold; text-transform: uppercase; }

        .page-num { text-align: right; font-size: 6.5pt; color: #aaa; margin-top: 6pt; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

@php
    $chunks      = $payments->chunk(20);
    $totalChunks = $chunks->count();
    $totalPaid   = $payments->sum('paid_amount');
    $totalCount  = $payments->count();
@endphp

@foreach($chunks as $pageIndex => $chunk)

<div class="{{ $pageIndex > 0 ? 'page-wrapper' : '' }}">

    @if($pageIndex === 0)
    {{-- ── First page header ── --}}
    <div class="page-header">
        <div>
            <h1>Ashis Auto Solution</h1>
            <div class="company-sub">Payroll Management System &mdash; Salary Payment Report</div>
            <div style="font-size:8pt; color:#444; margin-top:4pt;">
                Period: <strong>{{ $month ?? 'All' }}</strong>
                &nbsp;&middot;&nbsp;
                Type: <strong>{{ $paymentType ? ucfirst(str_replace('_',' ',$paymentType)) : 'All' }}</strong>
                &nbsp;&middot;&nbsp;
                {{ $totalCount }} record(s)
            </div>
        </div>
        <div style="text-align:right; font-size:7.5pt; color:#444;">
            Printed: {{ now()->format('d M Y, h:i A') }}<br>
            Page: 1 / {{ $totalChunks }}
        </div>
    </div>
    @else
    {{-- ── Page 2+ compact sub-header ── --}}
    <div class="sub-header">
        <div style="font-size:7.5pt; font-weight:bold;">
            Ashis Auto Solution &mdash; Salary Payment Report &mdash; {{ $month ?? 'All' }}
        </div>
        <div style="font-size:6.5pt; color:#888;">Page: {{ $pageIndex + 1 }} / {{ $totalChunks }}</div>
    </div>
    @endif

    {{-- ── Table ── --}}
    <table>
        <thead>
            <tr>
                <th colspan="4" class="group-emp">Employee Info</th>
                <th colspan="5" class="group-pay">Payment Details</th>
            </tr>
            <tr>
                <th class="col" style="width:4%;">SL</th>
                <th class="col" style="width:16%; text-align:left;">Employee</th>
                <th class="col" style="width:13%; text-align:left;">Designation</th>
                <th class="col" style="width:11%;">Total Salary (৳)</th>
                <th class="col" style="width:9%;">Month</th>
                <th class="col" style="width:11%;">Payment Type</th>
                <th class="col" style="width:11%;">Paid Amount (৳)</th>
                <th class="col" style="width:10%;">Date</th>
                <th class="col" style="width:15%;">Sign</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chunk as $i => $payment)
            @php $sl = ($pageIndex * 20) + $i + 1; @endphp
            <tr>
                <td class="td-sl">{{ $sl }}</td>
                <td class="td-name">{{ $payment->employee->name }}</td>
                <td class="td-des">{{ $payment->employee->designation }}</td>
                <td class="td-bold">{{ number_format($payment->employee->total_salary, 2) }}</td>
                <td class="td-center">{{ $payment->payroll->month }}</td>
                <td class="td-center">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                <td class="td-green">{{ number_format($payment->paid_amount, 2) }}</td>
                <td class="td-center" style="font-size:7pt;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                <td style="height:18pt;"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── Summary — last page only ── --}}
    @if($pageIndex === $totalChunks - 1)
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Total Records</span>
                <span class="summary-value">{{ $totalCount }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Unique Employees</span>
                <span class="summary-value">{{ $payments->pluck('employee_id')->unique()->count() }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Period</span>
                <span class="summary-value" style="font-size:8pt;">{{ $month ?? 'All' }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Paid Amount (৳)</span>
                <span class="summary-value">{{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Signatures — every page ── --}}
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