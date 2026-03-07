@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --bg:         #f5f5f0;
        --surface:    #ffffff;
        --border:     #e2e2dc;
        --border-md:  #d0d0c8;
        --text-primary:   #1a1a18;
        --text-secondary: #5a5a54;
        --text-muted:     #9a9a92;
        --accent:     #1a3a5c;
        --accent-lt:  #e8eef4;
        --gold:       #8a6a00;
        --gold-lt:    #fdf6e3;
        --green:      #1a5c3a;
        --green-lt:   #e8f4ee;
        --red:        #8a1a1a;
        --red-lt:     #fdf0f0;
    }

    * { box-sizing: border-box; }
    .pw * { font-family: 'Inter', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }

    .pw {
        min-height: 100vh;
        background: var(--bg);
        padding: 2rem 2.5rem;
    }

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
        font-family: 'Inter', sans-serif;
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

    .filter-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-card-title {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        align-items: end;
    }

    @media (max-width: 900px) { .filter-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 560px) { .filter-grid { grid-template-columns: 1fr; } }

    .filter-field label {
        display: block;
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--text-secondary);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
    }

    .filter-field input,
    .filter-field select {
        width: 100%;
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s;
        appearance: none;
    }

    .filter-field input:focus,
    .filter-field select:focus { border-color: var(--accent); }

    .filter-field select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%235a5a54'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.6rem center;
        background-size: 0.9rem;
        padding-right: 2rem;
    }

    .filter-field select option { background: #fff; color: var(--text-primary); }
    input[type="month"]::-webkit-calendar-picker-indicator { opacity: 0.5; cursor: pointer; }

    .filter-actions { display: flex; gap: 0.5rem; }

    .btn-filter {
        flex: 1;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: 5px;
        padding: 0.5rem 1rem;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
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
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-clear:hover { border-color: var(--red); color: var(--red); }

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
        background: var(--surface);
    }

    .table-card-title {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-secondary);
    }

    .table-card-count {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
    }

    .tbl-wrap { width: 100%; overflow-x: auto; }
    .tbl-wrap::-webkit-scrollbar { height: 4px; }
    .tbl-wrap::-webkit-scrollbar-track { background: var(--bg); }
    .tbl-wrap::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 2px; }

    .rpt-table * {
        font-family: 'Arial Narrow', Arial, sans-serif !important;
    }

    .rpt-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
        min-width: 2000px;
        font-size: 0.77rem;
        letter-spacing: -0.01em;
        font-family: 'Arial Narrow', Arial, sans-serif;
    }

    .grp-hd {
        font-family: 'Arial Narrow', Arial, sans-serif;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        padding: 0.5rem 0.75rem;
        text-align: center;
    }

    .grp-emp  { background: var(--accent-lt); color: var(--accent); border-bottom: 2px solid #c5d4e3; }
    .grp-pay  { background: var(--gold-lt);   color: var(--gold);   border-bottom: 2px solid #e8d8a0; }
    .grp-loan { background: var(--red-lt);    color: var(--red);    border-bottom: 2px solid #e8c5c5; }

    .col-hd {
        font-family: 'Arial Narrow', Arial, sans-serif;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-secondary);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.6rem 0.65rem;
        background: #f8f8f5;
        border-bottom: 2px solid var(--border-md);
        white-space: nowrap;
    }

    .rpt-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.1s; }
    .rpt-table tbody tr:hover { background: #f9f9f6; }
    .rpt-table tbody tr:last-child { border-bottom: none; }

    .rpt-table tbody td {
        padding: 0.6rem 0.65rem;
        vertical-align: middle;
        color: var(--text-secondary);
        white-space: nowrap;
        font-feature-settings: "tnum";
    }

    .c-name  { font-family: 'Arial Narrow', Arial, sans-serif; font-weight: 600; color: var(--text-primary); font-size: 0.76rem; letter-spacing: -0.01em; }
    .c-date  { font-family: 'Arial Narrow', Arial, sans-serif; font-size: 0.63rem; color: var(--text-muted); letter-spacing: 0; }
    .c-mono  { font-family: 'Arial Narrow', Arial, sans-serif; font-size: 0.68rem; font-feature-settings: "tnum"; }
    .c-amber { font-family: 'Arial Narrow', Arial, sans-serif; font-size: 0.68rem; font-weight: 600; color: var(--gold); font-feature-settings: "tnum"; }
    .c-green { font-family: 'Arial Narrow', Arial, sans-serif; font-size: 0.68rem; font-weight: 700; color: var(--green); font-feature-settings: "tnum"; }
    .c-red   { font-family: 'Arial Narrow', Arial, sans-serif; font-size: 0.68rem; color: var(--red); font-feature-settings: "tnum"; }

    .badge-des {
        display: inline-block;
        padding: 0.18rem 0.55rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-weight: 500;
        background: var(--accent-lt);
        color: var(--accent);
        white-space: nowrap;
    }

    .badge-mo {
        display: inline-block;
        padding: 0.18rem 0.55rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-family: 'Arial Narrow', Arial, sans-serif;
        background: var(--gold-lt);
        color: var(--gold);
    }

    .badge-absent {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 4px;
        font-size: 0.68rem;
        font-weight: 700;
        background: var(--red-lt);
        color: var(--red);
        font-family: 'Arial Narrow', Arial, sans-serif;
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
    }
    .slip-btn:hover { background: var(--green); color: #fff; }

    /* Pay First Half — navy */
    .pay-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 600;
        background: var(--accent-lt);
        color: var(--accent);
        border: 1px solid #c5d4e3;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .pay-btn:hover { background: var(--accent); color: #fff; }

    /* Pay Final Half — gold */
    .pay-btn-partial {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 600;
        background: var(--gold-lt);
        color: var(--gold);
        border: 1px solid #e8d8a0;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .pay-btn-partial:hover { background: var(--gold); color: #fff; }

    /* Full paid — green */
    .paid-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        padding: 0.22rem 0.55rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-weight: 600;
        background: var(--green-lt);
        color: var(--green);
        border: 1px solid #c5dfd0;
        white-space: nowrap;
    }

    /* Half done — gold */
    .half-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        padding: 0.22rem 0.55rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-weight: 600;
        background: var(--gold-lt);
        color: var(--gold);
        border: 1px solid #e8d8a0;
        white-space: nowrap;
    }

    /* Delete — red */
    .del-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        padding: 0.22rem 0.5rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-weight: 600;
        background: var(--red-lt);
        color: var(--red);
        border: 1px solid #e8c5c5;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .del-btn:hover { background: var(--red); color: #fff; }

    .action-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
    }

    .summary-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid var(--border);
    }

    .sum-item { padding: 1rem 1.5rem; border-right: 1px solid var(--border); }
    .sum-item:last-child { border-right: none; }

    .sum-label {
        font-family: 'Inter', sans-serif;
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
    .sum-value.v-red   { color: var(--red); }
    .sum-value.v-blue  { color: var(--accent); }

    .empty-state { padding: 4rem 2rem; text-align: center; color: var(--text-muted); }
    .empty-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; opacity: 0.3; }
    .empty-text { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; }

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
</style>

<div class="pw">
    <div class="relative w-full mx-auto">

        {{-- Page Header --}}
        <div class="corp-header">
            <div class="corp-header-left">
                <div class="eyebrow">Payroll Management System</div>
                <h1>Payroll Report</h1>
                <div class="sub">{{ $month }} &nbsp;·&nbsp; {{ count($employees) }} employee(s) found</div>
            </div>
            <div class="corp-header-right">
                <a href="{{ route('report.pdf', array_filter(['month' => $month, 'year' => $year ?? null, 'employee_id' => $employee_id ?? null])) }}"
                   class="btn btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('employee.list') }}" class="btn btn-outline">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Employees
                </a>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="filter-card">
            <div class="filter-card-title">Filter Report</div>
            <form method="GET" action="{{ route('report.index') }}">
                <div class="filter-grid">
                    <div class="filter-field">
                        <label>Employee</label>
                        <select name="employee_id">
                            <option value="">All Employees</option>
                            @foreach(App\Models\Employee::all() as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Year</label>
                        <input type="number" name="year" placeholder="e.g. 2026" value="{{ request('year') }}">
                    </div>
                    <div class="filter-field">
                        <label>Month</label>
                        <input type="month" name="month" value="{{ $month }}">
                    </div>
                    <div class="filter-field">
                        <label>&nbsp;</label>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                </svg>
                                Apply Filter
                            </button>
                            <a href="{{ route('report.index') }}" class="btn-clear" title="Clear">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="table-card">
            <div class="table-card-header">
                <span class="table-card-title">Employee Payroll Data</span>
                <span class="table-card-count">{{ count($employees) }} records</span>
            </div>

            <div class="tbl-wrap">
                <table class="rpt-table">
                    <thead>
                        <tr>
                            <th colspan="4"  class="grp-hd grp-emp">Employee Info</th>
                            <th colspan="6"  class="grp-hd grp-pay">Salary Components</th>
                            <th colspan="10" class="grp-hd grp-pay">Payroll — {{ $month }}</th>
                            <th colspan="3"  class="grp-hd grp-loan">Loan Info</th>
                            <th colspan="1"  class="grp-hd grp-emp">Action</th>
                        </tr>
                        <tr>
                            <th class="col-hd text-center">Slip</th>
                            <th class="col-hd">Name</th>
                            <th class="col-hd">Join Date</th>
                            <th class="col-hd">Designation</th>
                            <th class="col-hd text-right">Total Salary</th>
                            <th class="col-hd text-right">Basic</th>
                            <th class="col-hd text-right">House Rent</th>
                            <th class="col-hd text-right">Medical</th>
                            <th class="col-hd text-right">Conveyance</th>
                            <th class="col-hd text-right">Advance</th>
                            <th class="col-hd text-center">Month</th>
                            <th class="col-hd text-center">Absent</th>
                            <th class="col-hd text-center">Leave Used</th>
                            <th class="col-hd text-center">Cut Days</th>
                            <th class="col-hd text-right">Absent Amt</th>
                            <th class="col-hd text-right">Adv. Deduct</th>
                            <th class="col-hd text-right">Loan Deduct</th>
                            <th class="col-hd text-right">Rem. Leave</th>
                            <th class="col-hd text-right" style="background:#fff5f5;border-bottom:2px solid #e8c5c5;">Total Deduct</th>
                            <th class="col-hd text-right">Net Payable</th>
                            <th class="col-hd text-right">Loan Amt</th>
                            <th class="col-hd text-right">Monthly</th>
                            <th class="col-hd text-right">Remaining</th>
                            <th class="col-hd text-center">Status / Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        @php
                            $payroll          = $employee->payrolls->first();
                            $loan             = $employee->loans->first();
                            $absent           = $payroll->absent_days ?? 0;
                            $hasLoanInPayroll = $payroll && ($payroll->loan_deduction ?? 0) > 0;
                            $hasActiveLoan    = $loan && ($loan->remaining_amount ?? 0) > 0;
                            $advance          = $employee->advances->sum('amount');

                            // Payment logic based on payments relation
                            $totalPaid = $payroll ? $payroll->payments->sum('paid_amount') : 0;
                            $netSalary = $payroll ? ($payroll->net_salary ?? $payroll->net_payable ?? 0) : 0;
                            $remaining = $netSalary - $totalPaid;

                            // Total deduction
                            $totalDeduction = ($payroll->absent_amount ?? 0)
                                            + ($payroll->advance_deduction ?? 0)
                                            + ($payroll->loan_deduction ?? 0);
                        @endphp
                        <tr>
                            {{-- Slip --}}
                            <td class="text-center">
                                @if($payroll)
                                    <a href="{{ route('report.payslip', [$employee->id, $payroll->month]) }}" class="slip-btn">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Slip
                                    </a>
                                @else
                                    <span class="mono" style="color:var(--text-muted);font-size:0.7rem;">—</span>
                                @endif
                            </td>

                            {{-- Employee Info --}}
                            <td class="c-name">{{ $employee->name }}</td>
                            <td class="c-date">{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</td>
                            <td><span class="badge-des">{{ $employee->designation }}</span></td>

                            {{-- Salary Components --}}
                            <td class="c-amber text-right">৳{{ number_format($employee->total_salary, 2) }}</td>
                            <td class="c-mono  text-right">৳{{ number_format($employee->basic_salary, 2) }}</td>
                            <td class="c-mono  text-right">৳{{ number_format($employee->house_rent, 2) }}</td>
                            <td class="c-mono  text-right">৳{{ number_format($employee->medical, 2) }}</td>
                            <td class="c-mono  text-right">৳{{ number_format($employee->conveyance, 2) }}</td>
                            <td class="c-mono  text-right">
                                @if($advance > 0)
                                    <span style="color:var(--gold);font-weight:600;">৳{{ number_format($advance, 2) }}</span>
                                @else
                                    <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>

                            {{-- Payroll --}}
                            <td class="text-center">
                                @if($payroll)
                                    <span class="badge-mo">{{ $payroll->month }}</span>
                                @else
                                    <span class="mono" style="color:var(--text-muted);font-size:0.7rem;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($absent > 0)
                                    <span class="badge-absent">{{ $absent }}</span>
                                @else
                                    <span class="mono" style="color:var(--text-muted);font-size:0.7rem;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $leaveUsed = $payroll->leave_used ?? 0; @endphp
                                @if($leaveUsed > 0)
                                    <span class="badge-absent" style="background:var(--gold-lt);color:var(--gold);border:none;">{{ $leaveUsed }}</span>
                                @else
                                    <span class="mono" style="color:var(--text-muted);font-size:0.7rem;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $cutDays = $payroll->salary_cut_days ?? 0; @endphp
                                @if($cutDays > 0)
                                    <span class="badge-absent">{{ $cutDays }}</span>
                                @else
                                    <span class="mono" style="color:var(--text-muted);font-size:0.7rem;">—</span>
                                @endif
                            </td>
                            <td class="c-red text-right">
                                @if(($payroll->absent_amount ?? 0) > 0)
                                    ৳{{ number_format($payroll->absent_amount, 2) }}
                                @else <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td class="c-red text-right">
                                @if(($payroll->advance_deduction ?? 0) > 0)
                                    ৳{{ number_format($payroll->advance_deduction, 2) }}
                                @else <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td class="c-red text-right">
                                @if(($payroll->loan_deduction ?? 0) > 0)
                                    ৳{{ number_format($payroll->loan_deduction, 2) }}
                                @else <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <span class="mono" style="font-size:0.7rem;color:var(--green);font-weight:600;">{{ $employee->remaining_leave }}</span>
                            </td>

                            {{-- Total Deduction --}}
                            <td class="text-right" style="background:#fff8f8;">
                                @if($payroll && $totalDeduction > 0)
                                    <span style="font-family:'DM Mono',monospace;font-size:0.7rem;font-weight:700;color:var(--red);">
                                        ৳{{ number_format($totalDeduction, 2) }}
                                    </span>
                                @else
                                    <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>

                            {{-- Net Payable --}}
                            <td class="c-green text-right">
                                ৳{{ number_format($payroll->net_payable ?? $employee->total_salary, 2) }}
                            </td>

                            {{-- Loan Info --}}
                            <td class="c-mono text-right">
                                @if($hasLoanInPayroll || $hasActiveLoan)
                                    ৳{{ number_format($loan->loan_amount ?? 0, 2) }}
                                @else <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td class="c-mono text-right">
                                @if($hasLoanInPayroll)
                                    ৳{{ number_format($payroll->loan_deduction, 2) }}
                                @elseif($hasActiveLoan)
                                    ৳{{ number_format($loan->monthly_deduction, 2) }}
                                @else <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($hasLoanInPayroll || $hasActiveLoan)
                                    <span class="c-red">৳{{ number_format($loan->remaining_amount ?? 0, 2) }}</span>
                                @else
                                    <span class="mono" style="color:var(--green);font-size:0.65rem;font-weight:600;">CLEARED</span>
                                @endif
                            </td>

                            {{-- Status / Action --}}
                            <td class="text-center" style="white-space:normal;">
                                <div class="action-cell">
                                @if($payroll)
                                    @if($remaining <= 0)
                                        {{-- Fully Paid --}}
                                        <span class="paid-badge">
                                            <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Full Paid
                                        </span>
                                    @elseif($totalPaid > 0)
                                        {{-- Half done — show badge + Pay Final Half button --}}
                                        <span class="half-badge">
                                            <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Half Done
                                        </span>
                                        <form action="{{ route('salary.pay', $payroll->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="pay-btn-partial"
                                                    onclick="this.disabled=true;this.innerText='...';this.form.submit();">
                                                Pay Final Half
                                            </button>
                                        </form>
                                    @else
                                        {{-- Pending — Pay First Half --}}
                                        <form action="{{ route('salary.pay', $payroll->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="pay-btn"
                                                    onclick="this.disabled=true;this.innerText='...';this.form.submit();">
                                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Pay First Half
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete — only while not fully paid --}}
                                    @if($remaining > 0)
                                        <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure? This will reverse leave & loan deduction.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="del-btn">
                                                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="mono" style="color:var(--text-muted);font-size:0.7rem;">—</span>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="24">
                                <div class="empty-state">
                                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="empty-text">No payroll data for this period</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(count($employees) > 0)
            <div class="summary-bar" style="grid-template-columns: repeat(6, 1fr);">
                <div class="sum-item">
                    <div class="sum-label">Total Salary</div>
                    <div class="sum-value">৳{{ number_format($employees->sum('total_salary'), 2) }}</div>
                </div>
                <div class="sum-item">
                    <div class="sum-label">Net Payable</div>
                    <div class="sum-value v-green">৳{{ number_format($employees->sum(fn($e) => (float)($e->payrolls->first()->net_payable ?? $e->total_salary)), 2) }}</div>
                </div>
                <div class="sum-item" style="background:#fff8f8;">
                    <div class="sum-label">Total Deduction</div>
                    <div class="sum-value v-red">৳{{ number_format($employees->sum(fn($e) => ($e->payrolls->first()->absent_amount ?? 0) + ($e->payrolls->first()->advance_deduction ?? 0) + ($e->payrolls->first()->loan_deduction ?? 0)), 2) }}</div>
                </div>
                <div class="sum-item">
                    <div class="sum-label">Advance Total</div>
                    <div class="sum-value v-red">৳{{ number_format($employees->sum(fn($e) => $e->advances->sum('amount')), 2) }}</div>
                </div>
                <div class="sum-item">
                    <div class="sum-label">Loan Deductions</div>
                    <div class="sum-value v-red">৳{{ number_format($employees->sum(fn($e) => $e->payrolls->first()->loan_deduction ?? 0), 2) }}</div>
                </div>
                <div class="sum-item">
                    <div class="sum-label">Total Employees</div>
                    <div class="sum-value v-blue">{{ count($employees) }}</div>
                </div>
            </div>
            @endif

        </div>{{-- end .table-card --}}

        <div class="bottom-rule">End of Report</div>

    </div>
</div>

@endsection