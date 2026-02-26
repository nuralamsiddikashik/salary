@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap');

    .list-wrapper * { font-family: 'Syne', sans-serif; }
    .mono { font-family: 'Space Mono', monospace; }

    .accent-line {
        height: 3px;
        background: linear-gradient(90deg, #f59e0b, #ef4444, transparent);
    }

    .noise-overlay {
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        pointer-events: none;
    }

    .card-glow {
        box-shadow:
            0 0 0 1px rgba(245, 158, 11, 0.1),
            0 25px 50px -12px rgba(0, 0, 0, 0.8),
            inset 0 1px 0 rgba(255,255,255,0.03);
    }

    /* ── Table ── */
    .table-outer {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table-outer::-webkit-scrollbar { height: 3px; }
    .table-outer::-webkit-scrollbar-track { background: #111827; }
    .table-outer::-webkit-scrollbar-thumb { background: #f59e0b; border-radius: 2px; }

    .emp-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        min-width: 600px;
    }

    .emp-table colgroup col:nth-child(1) { width: 22%; }  /* Name */
    .emp-table colgroup col:nth-child(2) { width: 18%; }  /* Designation */
    .emp-table colgroup col:nth-child(3) { width: 15%; }  /* Total Salary */
    .emp-table colgroup col:nth-child(4) { width: 12%; }  /* Absent Days */
    .emp-table colgroup col:nth-child(5) { width: 17%; }  /* Loan Deduction */
    .emp-table colgroup col:nth-child(6) { width: 16%; }  /* Net Payable */

    .emp-table thead th {
        font-family: 'Space Mono', monospace;
        font-size: 0.55rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #f59e0b;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #1f2937;
        white-space: nowrap;
        background: rgba(12, 14, 18, 0.98);
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .emp-table tbody tr {
        border-bottom: 1px solid #111827;
        transition: background 0.2s ease;
    }
    .emp-table tbody tr:hover { background: rgba(245, 158, 11, 0.04); }
    .emp-table tbody tr:last-child { border-bottom: none; }

    .emp-table tbody td {
        padding: 0.85rem 1rem;
        color: #d1d5db;
        font-size: 0.82rem;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .name-cell   { font-weight: 700; color: #f9fafb; font-size: 0.88rem; }
    .date-cell   { font-family: 'Space Mono', monospace; font-size: 0.65rem; color: #6b7280; }

    .badge-designation {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        font-size: 0.62rem;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #fbbf24;
        letter-spacing: 0.04em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .salary-cell {
        font-family: 'Space Mono', monospace;
        font-size: 0.72rem;
        color: #9ca3af;
    }

    .total-salary-cell {
        font-family: 'Space Mono', monospace;
        font-size: 0.74rem;
        font-weight: 700;
        color: #f59e0b;
    }

    .net-cell {
        font-family: 'Space Mono', monospace;
        font-size: 0.74rem;
        font-weight: 700;
        color: #34d399;
    }

    .absent-cell {
        font-family: 'Space Mono', monospace;
        font-size: 0.72rem;
        color: #f87171;
        text-align: center;
    }

    .absent-zero {
        color: #4b5563;
    }

    .row-num {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        color: #374151;
        text-align: center;
    }

    /* Add button */
    .add-btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }
    .add-btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
        transition: left 0.5s ease;
    }
    .add-btn:hover::before { left: 100%; }
    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        background-color: #fbbf24;
    }

    .empty-state { font-family: 'Space Mono', monospace; letter-spacing: 0.1em; }

    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>

<div class="list-wrapper min-h-screen bg-gray-950 p-4 md:p-8 lg:p-10">

    {{-- Background grid --}}
    <div class="fixed inset-0 pointer-events-none opacity-5"
         style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="noise-overlay fixed inset-0"></div>

    <div class="relative w-full mx-auto">

        {{-- Page Header --}}
        <div class="page-header flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div style="height:2px; width:2.5rem; background: linear-gradient(90deg, #f59e0b, #ef4444, transparent);"></div>
                    <span class="mono text-xs text-gray-500 tracking-widest">PAYROLL MANAGEMENT</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight" style="font-family: 'Syne', sans-serif;">
                    Employee List
                </h1>
                <p class="mono text-xs text-gray-600 mt-1 tracking-wide">
                    {{ count($employees) }} RECORD(S) FOUND
                </p>
            </div>

            <a href="{{ route('employee.create') }}"
               class="add-btn bg-amber-500 text-gray-900 font-bold py-3 px-5 rounded-xl text-sm tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add Employee
            </a>
        </div>

        {{-- Main Card --}}
        <div class="card-glow bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">

            <div class="accent-line"></div>

            {{-- Table --}}
            <div class="table-outer">
                <table class="emp-table">
                    <colgroup>
                        <col/><col/><col/><col/><col/><col/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Designation</th>
                            <th class="text-right">Total Salary</th>
                            <th class="text-center">Absent Days</th>
                            <th class="text-right">Loan Deduction</th>
                            <th class="text-right">Net Payable</th>
                            <th>Pay Slip</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                      
                            {{-- Name --}}
                            <td class="name-cell">{{ $employee->name }}</td>

                            {{-- Designation --}}
                            <td>
                                <span class="badge-designation">{{ $employee->designation }}</span>
                            </td>

                            {{-- Total Salary --}}
                            <td class="total-salary-cell text-right">
                                ৳{{ number_format($employee->total_salary, 2) }}
                            </td>

                            {{-- Absent Days --}}
                            <td class="absent-cell {{ ($employee->latestPayroll->absent_days ?? 0) == 0 ? 'absent-zero' : '' }}">
                                @php $absent = $employee->latestPayroll->absent_days ?? 0; @endphp
                                @if($absent > 0)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold"
                                          style="background: rgba(248,113,113,0.12); border: 1px solid rgba(248,113,113,0.25); color: #f87171;">
                                        {{ $absent }}
                                    </span>
                                @else
                                    <span class="mono text-xs text-gray-700">—</span>
                                @endif
                            </td>

                            {{-- Loan Deduction --}}
                            <td class="salary-cell text-right">
                                @php $loan = $employee->latestPayroll->loan_deduction ?? 0; @endphp
                                @if($loan > 0)
                                    <span class="text-red-400">৳{{ number_format($loan, 2) }}</span>
                                @else
                                    <span class="text-gray-700 mono text-xs">—</span>
                                @endif
                            </td>

                            {{-- Net Payable --}}
                            <td class="net-cell text-right">
                                ৳{{ number_format($employee->latestPayroll->net_payable ?? $employee->total_salary, 2) }}
                            </td>
                        </tr>
                        @endforeach

                        @if(count($employees) === 0)
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="empty-state flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-800 border border-gray-700 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-600 tracking-widest">NO EMPLOYEES FOUND</p>
                                    <a href="{{ route('employee.create') }}"
                                       class="text-amber-500 text-xs tracking-widest border-b border-amber-500 border-opacity-30 pb-0.5 hover:border-opacity-100 transition-all">
                                        + ADD FIRST EMPLOYEE
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            @if(count($employees) > 0)
            <div class="px-5 py-4 border-t border-gray-800 flex items-center justify-between flex-wrap gap-3">
                {{-- Total counts --}}
                <span class="mono text-xs text-gray-600 tracking-widest">
                    TOTAL {{ count($employees) }} EMPLOYEES
                </span>

                {{-- Summary totals --}}
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="mono text-xs text-gray-600 tracking-widest mb-0.5">TOTAL PAYABLE</p>
                        <p class="mono text-sm font-bold text-emerald-400">
                            ৳{{ number_format($employees->sum(fn($e) => $e->latestPayroll->net_payable ?? $e->total_salary), 2) }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-amber-500 opacity-60 animate-pulse"></div>
                        <span class="mono text-xs text-gray-700 tracking-widest">LIVE DATA</span>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Bottom label --}}
        <div class="flex items-center gap-3 mt-6">
            <div style="flex:1; height:1px; background: linear-gradient(90deg, transparent, #374151);"></div>
            <span class="mono text-xs text-gray-700 tracking-widest">END OF RECORDS</span>
            <div style="flex:1; height:1px; background: linear-gradient(90deg, #374151, transparent);"></div>
        </div>

    </div>
</div>

@endsection