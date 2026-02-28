@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --bg:             #f5f5f0;
        --surface:        #ffffff;
        --border:         #e2e2dc;
        --border-md:      #d0d0c8;
        --text-primary:   #1a1a18;
        --text-secondary: #5a5a54;
        --text-muted:     #9a9a92;
        --accent:         #1a3a5c;
        --accent-lt:      #e8eef4;
        --gold:           #8a6a00;
        --gold-lt:        #fdf6e3;
        --green:          #1a5c3a;
        --green-lt:       #e8f4ee;
        --red:            #8a1a1a;
        --red-lt:         #fdf0f0;
    }

    * { box-sizing: border-box; }
    .pw * { font-family: 'Inter', sans-serif; }

    .pw {
        min-height: 100vh;
        background: var(--bg);
        padding: 2rem 2.5rem;
    }

    /* ── Page Header ── */
    .corp-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid var(--text-primary);
    }

    .corp-header-left .eyebrow {
        font-family: 'DM Mono', monospace;
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
    }

    .corp-header-left h1 {
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.03em;
        line-height: 1;
    }

    .corp-header-left .sub {
        font-size: 0.78rem;
        color: var(--text-secondary);
        margin-top: 0.35rem;
        font-weight: 400;
    }

    .corp-header-right {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    /* ── Buttons ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1rem;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-decoration: none;
        transition: all 0.15s ease;
        cursor: pointer;
        border: 1px solid transparent;
        font-family: 'Inter', sans-serif;
    }

    .btn-primary { background: var(--accent); color: #fff; border-color: var(--accent); }
    .btn-primary:hover { background: #142d47; }

    .btn-outline { background: var(--surface); color: var(--text-primary); border-color: var(--border-md); }
    .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

    /* ── Filter Card ── */
    .filter-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-end;
        gap: 1rem;
    }

    .filter-field { display: flex; flex-direction: column; gap: 0.35rem; }

    .filter-field label {
        font-size: 0.6rem;
        font-weight: 700;
        color: var(--text-secondary);
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .filter-field input[type="month"] {
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 0.48rem 0.75rem;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s;
    }
    .filter-field input[type="month"]:focus { border-color: var(--accent); }
    input[type="month"]::-webkit-calendar-picker-indicator { opacity: 0.5; cursor: pointer; }

    .filter-field select {
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 0.48rem 0.75rem;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%235a5a54'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.6rem center;
        background-size: 0.9rem;
        padding-right: 2rem;
        cursor: pointer;
    }
    .filter-field select:focus { border-color: var(--accent); }
    .filter-field select option { background: #fff; color: var(--text-primary); }

    .btn-filter {
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: 5px;
        padding: 0.5rem 1rem;
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: 'Inter', sans-serif;
        transition: background 0.15s;
    }
    .btn-filter:hover { background: #142d47; }

    .btn-clear {
        background: var(--surface);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        padding: 0.5rem 0.75rem;
        color: var(--text-secondary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-size: 0.72rem;
        transition: all 0.15s;
    }
    .btn-clear:hover { border-color: var(--red); color: var(--red); }

    @media (max-width: 560px) { .filter-card { flex-direction: column; align-items: stretch; } }

    /* ── Table Card ── */
    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
    }

    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .table-card-title {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-secondary);
    }

    .table-card-count {
        font-family: 'DM Mono', monospace;
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    /* ── Table ── */
    .tbl-wrap {
        width: 100%;
        overflow-x: auto;
    }
    .tbl-wrap::-webkit-scrollbar { height: 4px; }
    .tbl-wrap::-webkit-scrollbar-track { background: var(--bg); }
    .tbl-wrap::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 2px; }

    .pay-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.77rem;
        letter-spacing: -0.01em;
    }

    .pay-table thead tr {
        background: #f8f8f5;
        border-bottom: 2px solid var(--border-md);
    }

    .pay-table thead th {
        font-size: 0.6rem;
        font-weight: 700;
        color: var(--text-secondary);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.6rem 1rem;
        white-space: nowrap;
    }

    .pay-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.1s;
    }
    .pay-table tbody tr:hover { background: #f9f9f6; }
    .pay-table tbody tr:last-child { border-bottom: none; }

    .pay-table tbody td {
        padding: 0.65rem 1rem;
        vertical-align: middle;
        color: var(--text-secondary);
        font-feature-settings: "tnum";
    }

    /* Cell types */
    .c-name  { font-weight: 600; color: var(--text-primary); font-size: 0.78rem; letter-spacing: -0.01em; }
    .c-mono  { font-family: 'DM Mono', monospace; font-size: 0.7rem; }
    .c-date  { font-family: 'DM Mono', monospace; font-size: 0.68rem; color: var(--text-muted); }
    .c-green { font-family: 'DM Mono', monospace; font-size: 0.72rem; font-weight: 700; color: var(--green); font-feature-settings: "tnum"; }

    .badge-mo {
        display: inline-block;
        padding: 0.18rem 0.55rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-family: 'DM Mono', monospace;
        background: var(--gold-lt);
        color: var(--gold);
    }

    .badge-type {
        display: inline-block;
        padding: 0.18rem 0.6rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-weight: 600;
        background: var(--accent-lt);
        color: var(--accent);
        white-space: nowrap;
    }

    .slip-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 600;
        background: var(--green-lt);
        color: var(--green);
        text-decoration: none;
        border: 1px solid #c5dfd0;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .slip-btn:hover { background: var(--green); color: #fff; }

    /* ── Summary Bar ── */
    .summary-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border-top: 1px solid var(--border);
    }

    .sum-item {
        padding: 1rem 1.5rem;
        border-right: 1px solid var(--border);
    }
    .sum-item:last-child { border-right: none; }

    .sum-label {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.3rem;
    }

    .sum-value {
        font-family: 'DM Mono', monospace;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-primary);
        font-feature-settings: "tnum";
        letter-spacing: -0.02em;
    }
    .sum-value.v-green { color: var(--green); }
    .sum-value.v-blue  { color: var(--accent); }

    /* ── Empty State ── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; opacity: 0.3; }
    .empty-text { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; }

    /* ── Bottom rule ── */
    .bottom-rule {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        color: var(--text-muted);
        font-size: 0.65rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }
    .bottom-rule::before,
    .bottom-rule::after { content: ''; flex: 1; height: 1px; background: var(--border-md); }

    /* ── Print ── */
    @media print {
        @page { size: A4 landscape; margin: 10mm; }

        .no-print { display: none !important; }
        .slip-col { display: none !important; }

        html, body {
            background: #fff !important;
            margin: 0 !important; padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .pw { background: #fff !important; padding: 0 !important; }
        .table-card { border: none !important; border-radius: 0 !important; }
        .table-card-header { display: none !important; }
        .tbl-wrap { overflow: visible !important; }

        .print-header {
            display: flex !important;
            justify-content: space-between !important;
            border-bottom: 2px solid #000 !important;
            padding-bottom: 5pt !important;
            margin-bottom: 8pt !important;
        }
        .print-header h2 { font-size: 12pt !important; font-weight: 700 !important; }
        .print-header p  { font-size: 7pt !important; color: #444 !important; }

        .pay-table { font-size: 7pt !important; }
        .pay-table thead th { font-size: 6pt !important; padding: 3pt 5pt !important; background: #f4f4f0 !important; }
        .pay-table tbody td { font-size: 7pt !important; padding: 3.5pt 5pt !important; color: #000 !important; border-bottom: 1px solid #e0e0e0 !important; }
        .pay-table tbody tr:nth-child(even) td { background: #f7f7f5 !important; }

        .badge-mo, .badge-type {
            background: none !important; border: none !important;
            padding: 0 !important; color: #000 !important;
            font-size: 7pt !important; display: inline !important;
        }
        .c-name, .c-mono, .c-date, .c-green { color: #000 !important; font-size: 7pt !important; }
        .c-green { font-weight: 700 !important; }

        .summary-bar {
            display: flex !important; flex-wrap: wrap !important;
            gap: 5pt !important; border-top: 1px solid #ccc !important;
            margin-top: 8pt !important; padding-top: 6pt !important;
        }
        .sum-item {
            flex: 0 0 auto !important; width: 55mm !important;
            border: 1px solid #ddd !important; padding: 4pt 6pt !important;
            background: #f9f9f7 !important;
            -webkit-print-color-adjust: exact !important;
        }
        .sum-label { font-size: 5pt !important; }
        .sum-value { font-size: 9pt !important; color: #000 !important; }
    }
</style>

<div class="pw">
    <div class="relative w-full mx-auto">

        {{-- ── Page Header ── --}}
        <div class="corp-header no-print">
            <div class="corp-header-left">
                <div class="eyebrow">Payroll Management System</div>
                <h1>Payment Report</h1>
                <div class="sub">{{ $month }} &nbsp;·&nbsp; {{ count($payments) }} record(s)</div>
            </div>
            <div class="corp-header-right">
                <button onclick="window.print()" class="btn btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
                <a href="{{ route('report.index') }}" class="btn btn-outline">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        {{-- ── Filter Card ── --}}
        <div class="filter-card no-print">
            <form method="GET" action="{{ route('salary.payment.report') }}" style="display:flex;align-items:flex-end;gap:1rem;flex-wrap:wrap;">
                <div class="filter-field">
                    <label>Month</label>
                    <input type="month" name="month" value="{{ $month }}">
                </div>
                <div class="filter-field">
                    <label>Payment Type</label>
                    <select name="payment_type">
                        <option value="">All</option>
                        <option value="first_half" {{ request('payment_type') == 'first_half' ? 'selected' : '' }}>First Half</option>
                        <option value="final_half" {{ request('payment_type') == 'final_half' ? 'selected' : '' }}>Final Half</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Apply Filter
                </button>
                <a href="{{ route('salary.payment.report') }}" class="btn-clear" title="Clear">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </form>
        </div>

        {{-- ── Table Card ── --}}
        <div class="table-card">

            {{-- Print header --}}
            <div class="print-header" style="display:none;">
                <div>
                    <h2>Salary Payment Report</h2>
                    <p>Period: {{ $month }}</p>
                </div>
                <div style="text-align:right;">
                    <p>Printed: {{ now()->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            <div class="table-card-header no-print">
                <span class="table-card-title">Payment Records</span>
                <span class="table-card-count">{{ count($payments) }} records</span>
            </div>

            <div class="tbl-wrap">
                <table class="pay-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Payment Type</th>
                            <th class="text-right">Amount (৳)</th>
                            <th>Date</th>
                            <th class="slip-col text-center">Slip</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $i => $payment)
                        <tr>
                            <td class="c-mono" style="color:var(--text-muted);">{{ $i + 1 }}</td>
                            <td class="c-name">{{ $payment->employee->name }}</td>
                            <td><span class="badge-mo">{{ $payment->payroll->month }}</span></td>
                            <td>
                                <span class="badge-type">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                </span>
                            </td>
                            <td class="c-green text-right">৳{{ number_format($payment->paid_amount, 2) }}</td>
                            <td class="c-date">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            <td class="slip-col text-center">
                                <a href="{{ route('salary.payment.slip', $payment->id) }}" target="_blank" class="slip-btn">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Slip
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="empty-text">No payment records for this period</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── Summary Bar ── --}}
            @if(count($payments) > 0)
            <div class="summary-bar">
                <div class="sum-item">
                    <div class="sum-label">Total Payments</div>
                    <div class="sum-value v-blue">{{ count($payments) }}</div>
                </div>
                <div class="sum-item">
                    <div class="sum-label">Total Paid</div>
                    <div class="sum-value v-green">৳{{ number_format($payments->sum('paid_amount'), 2) }}</div>
                </div>
                <div class="sum-item">
                    <div class="sum-label">Period</div>
                    <div class="sum-value" style="font-size:0.8rem;">{{ $month }}</div>
                </div>
            </div>
            @endif

        </div>{{-- end .table-card --}}

        <div class="bottom-rule no-print">End of Report</div>

    </div>
</div>

@endsection