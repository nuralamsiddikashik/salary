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

    /* ── Stat Cards ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        transition: box-shadow 0.15s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(26,58,92,0.07); }

    .stat-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-label {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
    }

    .stat-icon {
        width: 30px; height: 30px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-icon.blue   { background: var(--accent-lt); color: var(--accent); }
    .stat-icon.green  { background: var(--green-lt);  color: var(--green); }
    .stat-icon.gold   { background: var(--gold-lt);   color: var(--gold); }
    .stat-icon.red    { background: var(--red-lt);    color: var(--red); }

    .stat-value {
        font-family: 'DM Mono', monospace;
        font-size: 1.65rem;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.04em;
        line-height: 1;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.65rem;
        color: var(--text-muted);
        padding-top: 0.6rem;
        border-top: 1px solid var(--border);
    }

    .stat-delta {
        font-weight: 700;
        font-size: 0.65rem;
        font-family: 'DM Mono', monospace;
    }
    .stat-delta.up   { color: var(--green); }
    .stat-delta.down { color: var(--red); }

    /* ── Two col layout ── */
    .dash-row {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .dash-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    /* ── Panel card (reusable) ── */
    .panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
    }

    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: #f8f8f5;
    }

    .panel-title {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-secondary);
    }

    .panel-action {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        letter-spacing: 0.04em;
        transition: opacity 0.15s;
    }
    .panel-action:hover { opacity: 0.7; }

    .panel-body { padding: 1.1rem 1.25rem; }

    /* ── Recent Payments Table ── */
    .mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.74rem;
    }

    .mini-table thead tr {
        border-bottom: 2px solid var(--border-md);
    }

    .mini-table thead th {
        font-size: 0.58rem;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0 0.75rem 0.55rem;
        white-space: nowrap;
    }

    .mini-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.1s;
    }
    .mini-table tbody tr:hover { background: #f9f9f6; }
    .mini-table tbody tr:last-child { border-bottom: none; }

    .mini-table tbody td {
        padding: 0.6rem 0.75rem;
        color: var(--text-secondary);
        vertical-align: middle;
    }

    .td-name  { font-weight: 600; color: var(--text-primary); font-size: 0.75rem; }
    .td-mono  { font-family: 'DM Mono', monospace; font-size: 0.68rem; }
    .td-green { font-family: 'DM Mono', monospace; font-size: 0.72rem; font-weight: 700; color: var(--green); }
    .td-gold  { font-family: 'DM Mono', monospace; font-size: 0.68rem; font-weight: 600; color: var(--gold); }

    .badge-sm {
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        font-size: 0.6rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-blue  { background: var(--accent-lt); color: var(--accent); }
    .badge-green { background: var(--green-lt);  color: var(--green); }
    .badge-gold  { background: var(--gold-lt);   color: var(--gold); }
    .badge-red   { background: var(--red-lt);    color: var(--red); }

    /* ── Quick Links ── */
    .quick-grid {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .quick-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 0.85rem;
        border-radius: 6px;
        border: 1px solid var(--border);
        text-decoration: none;
        transition: all 0.15s;
        background: var(--bg);
    }
    .quick-link:hover {
        border-color: var(--accent);
        background: var(--accent-lt);
    }

    .quick-link-icon {
        width: 28px; height: 28px;
        border-radius: 6px;
        background: var(--surface);
        border: 1px solid var(--border-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        flex-shrink: 0;
        transition: all 0.15s;
    }
    .quick-link:hover .quick-link-icon {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    .quick-link-text {
        font-size: 0.74rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .quick-link-sub {
        font-size: 0.62rem;
        color: var(--text-muted);
        margin-top: 1px;
    }

    .quick-link-arrow {
        margin-left: auto;
        color: var(--text-muted);
        transition: transform 0.15s, color 0.15s;
    }
    .quick-link:hover .quick-link-arrow {
        transform: translateX(3px);
        color: var(--accent);
    }

    /* ── Summary Bar ── */
    .summary-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border-top: 1px solid var(--border);
    }

    .sum-item {
        padding: 0.85rem 1.25rem;
        border-right: 1px solid var(--border);
    }
    .sum-item:last-child { border-right: none; }

    .sum-label {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .sum-value {
        font-family: 'DM Mono', monospace;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }
    .sum-value.v-green { color: var(--green); }
    .sum-value.v-blue  { color: var(--accent); }

    /* ── Activity list ── */
    .activity-list { display: flex; flex-direction: column; }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.7rem 0;
        border-bottom: 1px solid var(--border);
    }
    .activity-item:last-child { border-bottom: none; }

    .activity-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        margin-top: 4px;
        flex-shrink: 0;
    }
    .dot-green { background: var(--green); }
    .dot-blue  { background: var(--accent); }
    .dot-gold  { background: var(--gold); }
    .dot-red   { background: var(--red); }

    .activity-text {
        font-size: 0.72rem;
        color: var(--text-secondary);
        line-height: 1.4;
        flex: 1;
    }
    .activity-text strong { color: var(--text-primary); font-weight: 600; }

    .activity-time {
        font-family: 'DM Mono', monospace;
        font-size: 0.6rem;
        color: var(--text-muted);
        white-space: nowrap;
        margin-top: 2px;
    }

    /* ── Progress bars ── */
    .progress-list { display: flex; flex-direction: column; gap: 1rem; }

    .progress-item {}

    .progress-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.4rem;
    }

    .progress-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .progress-val {
        font-family: 'DM Mono', monospace;
        font-size: 0.65rem;
        color: var(--text-muted);
    }

    .progress-bar {
        height: 5px;
        background: var(--border);
        border-radius: 99px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 99px;
        background: var(--accent);
        transition: width 0.6s ease;
    }
    .progress-fill.green { background: var(--green); }
    .progress-fill.gold  { background: var(--gold); }
    .progress-fill.red   { background: var(--red); }

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

    /* ── Responsive ── */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .dash-row   { grid-template-columns: 1fr; }
        .dash-row-3 { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 640px) {
        .pw { padding: 1.25rem 1rem; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .dash-row-3 { grid-template-columns: 1fr; }
        .corp-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .summary-bar { grid-template-columns: 1fr; }
        .sum-item { border-right: none; border-bottom: 1px solid var(--border); }
        .sum-item:last-child { border-bottom: none; }
    }

    @media (max-width: 420px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="pw">
    <div class="relative w-full mx-auto">

        {{-- ── Page Header ── --}}
        <div class="corp-header">
            <div class="corp-header-left">
                <div class="eyebrow">Payroll Management System</div>
                <h1>Dashboard</h1>
                <div class="sub">
                    Welcome back, <strong>{{ auth()->user()->name }}</strong>
                    &nbsp;·&nbsp;
                    {{ now()->format('d M Y') }}
                </div>
            </div>
            <div class="corp-header-right">
                <a href="{{ route('payroll.create') }}" class="btn btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Payroll
                </a>
                <a href="{{ route('report.index') }}" class="btn btn-outline">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Reports
                </a>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-card-top">
                    <span class="stat-label">Total Employees</span>
                    <div class="stat-icon blue">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H2v-2a4 4 0 014-4h6a4 4 0 014 4v2zm0 0h2v-2a4 4 0 00-3-3.87M13 7a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <div class="stat-value">{{ $totalEmployees ?? '—' }}</div>
                <div class="stat-footer">
                    <span class="stat-delta up">↑ 2</span>
                    <span>this month</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <span class="stat-label">Total Paid</span>
                    <div class="stat-icon green">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <div class="stat-value">৳{{ number_format($totalPaid ?? 0, 0) }}</div>
                <div class="stat-footer">
                    <span class="stat-delta up">↑ 4.2%</span>
                    <span>vs last month</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <span class="stat-label">Active Loans</span>
                    <div class="stat-icon gold">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><polyline points="17 6 23 6 23 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <div class="stat-value">{{ $activeLoans ?? '—' }}</div>
                <div class="stat-footer">
                    <span class="stat-delta down">↓ 1</span>
                    <span>vs last month</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-top">
                    <span class="stat-label">Pending Payroll</span>
                    <div class="stat-icon red">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingPayroll ?? '—' }}</div>
                <div class="stat-footer">
                    <span>awaiting processing</span>
                </div>
            </div>

        </div>

        {{-- ── Row 1: Recent Payments + Quick Links ── --}}
        <div class="dash-row">

            {{-- Recent Payments --}}
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Recent Payments</span>
                    <a href="{{ route('salary.payment.report') }}" class="panel-action">View All →</a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding-left:1.25rem;">#</th>
                                <th style="text-align:left;">Employee</th>
                                <th style="text-align:left;">Month</th>
                                <th style="text-align:left;">Type</th>
                                <th style="text-align:right;">Amount</th>
                                <th style="text-align:center;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments ?? [] as $i => $p)
                            <tr>
                                <td class="td-mono" style="color:var(--text-muted); padding-left:1.25rem;">{{ $i + 1 }}</td>
                                <td class="td-name"></td>
                                <td><span class="badge-sm badge-gold"></span></td>
                                <td><span class="badge-sm badge-blue"></span></td>
                                <td class="td-green" style="text-align:right;">৳</td>
                                <td class="td-mono" style="text-align:center; color:var(--text-muted);"></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="padding:2.5rem; text-align:center; color:var(--text-muted); font-size:0.72rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase;">
                                    No payments recorded yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(isset($recentPayments) && count($recentPayments) > 0)
                <div class="summary-bar">
                    <div class="sum-item">
                        <div class="sum-label">Payments</div>
                        <div class="sum-value v-blue">{{ count($recentPayments) }}</div>
                    </div>
                    <div class="sum-item">
                        <div class="sum-label">Total</div>
                        <div class="sum-value v-green">৳{{ number_format($recentPayments->sum('paid_amount'), 2) }}</div>
                    </div>
                    <div class="sum-item">
                        <div class="sum-label">Period</div>
                        <div class="sum-value" style="font-size:0.72rem;">{{ now()->format('M Y') }}</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Quick Actions</span>
                </div>
                <div class="panel-body">
                    <div class="quick-grid">

                        <a href="{{ route('employee.list') }}" class="quick-link">
                            <div class="quick-link-icon">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H2v-2a4 4 0 014-4h6a4 4 0 014 4v2zm4-4v2h-2v-2a4 4 0 00-3-3.87M13 7a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="quick-link-text">Employee List</div>
                                <div class="quick-link-sub">View all employees</div>
                            </div>
                            <svg class="quick-link-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="9 18 15 12 9 6"/></svg>
                        </a>

                        <a href="{{ route('payroll.create') }}" class="quick-link">
                            <div class="quick-link-icon">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2"/><line x1="2" y1="10" x2="22" y2="10" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <div class="quick-link-text">Create Payroll</div>
                                <div class="quick-link-sub">Process monthly salary</div>
                            </div>
                            <svg class="quick-link-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="9 18 15 12 9 6"/></svg>
                        </a>

                        <a href="{{ route('loan.create') }}" class="quick-link">
                            <div class="quick-link-icon">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23" stroke-linecap="round" stroke-width="2"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke-linecap="round" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <div class="quick-link-text">New Loan</div>
                                <div class="quick-link-sub">Add employee loan</div>
                            </div>
                            <svg class="quick-link-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="9 18 15 12 9 6"/></svg>
                        </a>

                        <a href="{{ route('advance.create') }}" class="quick-link">
                            <div class="quick-link-icon">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18" stroke-linecap="round" stroke-width="2"/><polyline points="17 6 23 6 23 12" stroke-linecap="round" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <div class="quick-link-text">New Advance</div>
                                <div class="quick-link-sub">Issue salary advance</div>
                            </div>
                            <svg class="quick-link-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="9 18 15 12 9 6"/></svg>
                        </a>

                        <a href="{{ route('report.index') }}" class="quick-link">
                            <div class="quick-link-icon">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10" stroke-linecap="round" stroke-width="2"/><line x1="12" y1="20" x2="12" y2="4" stroke-linecap="round" stroke-width="2"/><line x1="6" y1="20" x2="6" y2="14" stroke-linecap="round" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <div class="quick-link-text">View Reports</div>
                                <div class="quick-link-sub">Analytics &amp; exports</div>
                            </div>
                            <svg class="quick-link-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="9 18 15 12 9 6"/></svg>
                        </a>

                        <a href="{{ route('salary.payment.report') }}" class="quick-link">
                            <div class="quick-link-icon">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke-linecap="round" stroke-width="2"/><polyline points="14 2 14 8 20 8" stroke-linecap="round" stroke-width="2"/><line x1="16" y1="13" x2="8" y2="13" stroke-linecap="round" stroke-width="2"/><line x1="16" y1="17" x2="8" y2="17" stroke-linecap="round" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <div class="quick-link-text">Salary Payments</div>
                                <div class="quick-link-sub">Payment records</div>
                            </div>
                            <svg class="quick-link-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="9 18 15 12 9 6"/></svg>
                        </a>

                    </div>
                </div>
            </div>

        </div>

        {{-- ── Row 2: Department Breakdown + Activity ── --}}
        <div class="dash-row">

            {{-- Department / Salary breakdown --}}
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Salary Disbursement</span>
                    <span style="font-family:'DM Mono',monospace;font-size:0.65rem;color:var(--text-muted);">{{ now()->format('M Y') }}</span>
                </div>
                <div class="panel-body">
                    <div class="progress-list">
                        <div class="progress-item">
                            <div class="progress-top">
                                <span class="progress-label">First Half Paid</span>
                                <span class="progress-val">৳</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill green" style="width: "></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-top">
                                <span class="progress-label">Final Half Paid</span>
                                <span class="progress-val">৳</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $finalHalfPct ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-top">
                                <span class="progress-label">Loan Deductions</span>
                                <span class="progress-val">৳{{ number_format($loanDeductions ?? 0) }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill gold" style="width: {{ $loanPct ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-top">
                                <span class="progress-label">Advance Deductions</span>
                                <span class="progress-val">৳{{ number_format($advanceDeductions ?? 0) }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill red" style="width: {{ $advancePct ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="summary-bar">
                    <div class="sum-item">
                        <div class="sum-label">Total Salary Bill</div>
                        <div class="sum-value v-blue">৳{{ number_format($totalSalaryBill ?? 0) }}</div>
                    </div>
                    <div class="sum-item">
                        <div class="sum-label">Total Disbursed</div>
                        <div class="sum-value v-green">৳{{ number_format($totalDisbursed ?? 0) }}</div>
                    </div>
                    <div class="sum-item">
                        <div class="sum-label">Remaining</div>
                        <div class="sum-value">৳{{ number_format(($totalSalaryBill ?? 0) - ($totalDisbursed ?? 0)) }}</div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="panel">
                <div class="panel-head">
                    <span class="panel-title">Recent Activity</span>
                </div>
                <div class="panel-body" style="padding-top:0.5rem; padding-bottom:0.5rem;">
                    <div class="activity-list">

                        @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-dot dot-{"></div>
                            <div style="flex:1;">
                                <div class="activity-text"></div>
                                <div class="activity-time"></div>
                            </div>
                        </div>
                        @empty
                        {{-- Static fallback --}}
                        <div class="activity-item">
                            <div class="activity-dot dot-green"></div>
                            <div style="flex:1;">
                                <div class="activity-text"><strong>Payroll processed</strong> for {{ now()->format('M Y') }}</div>
                                <div class="activity-time">just now</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot dot-blue"></div>
                            <div style="flex:1;">
                                <div class="activity-text">System ready — dashboard loaded</div>
                                <div class="activity-time">{{ now()->format('h:i A') }}</div>
                            </div>
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>

        <div class="bottom-rule">Ashis Auto Solution &mdash; Payroll Management System</div>

    </div>
</div>

@endsection