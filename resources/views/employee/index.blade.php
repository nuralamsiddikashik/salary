@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

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
    .lw * { font-family: 'DM Sans', sans-serif; }

    .lw {
        min-height: 100vh;
        background: var(--bg);
        padding: 2rem 2.5rem;
    }

    /* ── Page Header ── */
    .page-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid var(--text-primary);
    }

    .page-head-left .eyebrow {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
    }

    .page-head-left h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        line-height: 1;
    }

    .page-head-left .sub {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.35rem;
        font-family: 'DM Mono', monospace;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1.1rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        text-decoration: none;
        transition: all 0.15s;
        letter-spacing: 0.02em;
    }
    .btn-primary:hover { background: #142d47; transform: translateY(-1px); }

    /* ── Table Card ── */
    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
    }

    .table-card-head {
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
        font-size: 0.72rem;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
    }

    /* ── Table ── */
    .tbl-wrap {
        width: 100%;
        overflow-x: auto;
    }
    .tbl-wrap::-webkit-scrollbar { height: 4px; }
    .tbl-wrap::-webkit-scrollbar-track { background: var(--bg); }
    .tbl-wrap::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 2px; }

    .emp-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        min-width: 640px;
        font-size: 0.8rem;
    }

    .emp-table colgroup col:nth-child(1) { width: 22%; }
    .emp-table colgroup col:nth-child(2) { width: 20%; }
    .emp-table colgroup col:nth-child(3) { width: 16%; }
    .emp-table colgroup col:nth-child(4) { width: 10%; }
    .emp-table colgroup col:nth-child(5) { width: 16%; }
    .emp-table colgroup col:nth-child(6) { width: 16%; }

    .emp-table thead th {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--text-secondary);
        letter-spacing: 0.04em;
        padding: 0.6rem 1rem;
        background: #fafaf8;
        border-bottom: 1px solid var(--border-md);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .emp-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.1s;
    }
    .emp-table tbody tr:hover { background: #f9f9f6; }
    .emp-table tbody tr:last-child { border-bottom: none; }

    .emp-table tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        color: var(--text-secondary);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .c-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.82rem;
    }

    .badge-des {
        display: inline-block;
        padding: 0.18rem 0.55rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 500;
        background: var(--accent-lt);
        color: var(--accent);
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .c-gold {
        font-family: 'DM Mono', monospace;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gold);
    }

    .c-green {
        font-family: 'DM Mono', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--green);
    }

    .c-red {
        font-family: 'DM Mono', monospace;
        font-size: 0.73rem;
        color: var(--red);
    }

    .c-mono {
        font-family: 'DM Mono', monospace;
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    .badge-absent {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.6rem;
        height: 1.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        background: var(--red-lt);
        color: var(--red);
        font-family: 'DM Mono', monospace;
        border: 1px solid #e8c5c5;
    }

    /* ── Table Footer ── */
    .table-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 0.85rem 1.5rem;
        border-top: 1px solid var(--border);
        background: #fafaf8;
    }

    .foot-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .foot-total {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .foot-stat { text-align: right; }

    .foot-stat-label {
        font-size: 0.62rem;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.15rem;
    }

    .foot-stat-value {
        font-family: 'DM Mono', monospace;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--green);
    }

    /* ── Empty State ── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 3rem;
        height: 3rem;
        margin: 0 auto 1rem;
        color: var(--border-md);
    }

    .empty-label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
    }

    .empty-link {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        border-bottom: 1px solid var(--accent-lt);
        padding-bottom: 1px;
        transition: border-color 0.15s;
    }
    .empty-link:hover { border-color: var(--accent); }

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
    .bottom-rule::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-md);
    }

    @media (max-width: 768px) {
        .lw { padding: 1.25rem; }
        .page-head { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>

<div class="lw">
    <div class="relative w-full mx-auto">

        {{-- Page Header --}}
        <div class="page-head">
            <div class="page-head-left">
                <div class="eyebrow">Payroll Management System</div>
                <h1>Employee List</h1>
                <div class="sub">{{ count($employees) }} record(s) found</div>
            </div>
            <a href="{{ route('employee.create') }}" class="btn-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add Employee
            </a>
        </div>

        {{-- Table Card --}}
        <div class="table-card">

            <div class="table-card-head">
                <span class="table-card-title">All Employees</span>
                <span class="table-card-count">{{ count($employees) }} records</span>
            </div>

            <div class="tbl-wrap">
                <table class="emp-table">
                    <colgroup>
                        <col/><col/><col/><col/><col/><col/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Designation</th>
                            <th class="text-right">Total Salary</th>
                            <th class="text-center">Absent</th>
                            <th class="text-right">Loan Deduction</th>
                            <th class="text-right">Net Payable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        @php
                            $absent = $employee->latestPayroll->absent_days ?? 0;
                            $loan   = $employee->latestPayroll->loan_deduction ?? 0;
                            $net    = $employee->latestPayroll->net_payable ?? $employee->total_salary;
                        @endphp
                        <tr>
                            <td class="c-name">{{ $employee->name }}</td>
                            <td><span class="badge-des">{{ $employee->designation }}</span></td>
                            <td class="c-gold text-right">৳{{ number_format($employee->total_salary, 2) }}</td>
                            <td class="text-center">
                                @if($absent > 0)
                                    <span class="badge-absent">{{ $absent }}</span>
                                @else
                                    <span class="c-mono">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($loan > 0)
                                    <span class="c-red">৳{{ number_format($loan, 2) }}</span>
                                @else
                                    <span class="c-mono">—</span>
                                @endif
                            </td>
                            <td class="c-green text-right">৳{{ number_format($net, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div class="empty-label">No employees found</div>
                                    <a href="{{ route('employee.create') }}" class="empty-link">+ Add First Employee</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            @if(count($employees) > 0)
            <div class="table-foot">
                <span class="foot-label">Total {{ count($employees) }} Employees</span>
                <div class="foot-total">
                    <div class="foot-stat">
                        <div class="foot-stat-label">Total Net Payable</div>
                        <div class="foot-stat-value">
                            ৳{{ number_format($employees->sum(fn($e) => $e->latestPayroll->net_payable ?? $e->total_salary), 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div class="bottom-rule">End of Records</div>

    </div>
</div>

@endsection