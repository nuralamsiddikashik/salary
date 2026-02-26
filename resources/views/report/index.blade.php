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

    /* ── Filter Bar ── */
    .filter-input {
        background: transparent;
        border: none;
        border-bottom: 2px solid #374151;
        color: #f9fafb;
        padding: 0.4rem 0;
        outline: none;
        font-family: 'Space Mono', monospace;
        font-size: 0.82rem;
        transition: border-color 0.3s;
        width: 100%;
    }
    .filter-input:focus { border-bottom-color: #f59e0b; }
    input[type="month"].filter-input::-webkit-calendar-picker-indicator {
        filter: invert(0.3) sepia(1) saturate(3) hue-rotate(0deg);
        opacity: 0.5; cursor: pointer;
    }
    input[type="month"].filter-input::-webkit-calendar-picker-indicator:hover { opacity: 1; }

    .filter-select {
        background: transparent;
        border: none;
        border-bottom: 2px solid #374151;
        color: #f9fafb;
        padding: 0.4rem 0;
        outline: none;
        font-family: 'Space Mono', monospace;
        font-size: 0.82rem;
        transition: border-color 0.3s;
        width: 100%;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.25rem center;
        background-size: 1rem;
        padding-right: 1.5rem;
    }
    .filter-select:focus { border-bottom-color: #f59e0b; }
    .filter-select option { background: #1f2937; color: #f9fafb; }

    .filter-btn {
        position: relative; overflow: hidden;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    .filter-btn::before {
        content: '';
        position: absolute; top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
        transition: left 0.5s ease;
    }
    .filter-btn:hover::before { left: 100%; }
    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245,158,11,0.25);
        background-color: #fbbf24;
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

    .report-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        min-width: 1300px;
    }

    /* Column widths — 17 cols total (added Pay Slip col) */
    .report-table colgroup col:nth-child(1)  { width: 5%; }   /* Pay Slip */
    .report-table colgroup col:nth-child(2)  { width: 8%; }   /* Name */
    .report-table colgroup col:nth-child(3)  { width: 7%; }   /* Join Date */
    .report-table colgroup col:nth-child(4)  { width: 8%; }   /* Designation */
    .report-table colgroup col:nth-child(5)  { width: 7%; }   /* Total Salary */
    .report-table colgroup col:nth-child(6)  { width: 6%; }   /* Basic */
    .report-table colgroup col:nth-child(7)  { width: 6%; }   /* House Rent */
    .report-table colgroup col:nth-child(8)  { width: 5%; }   /* Medical */
    .report-table colgroup col:nth-child(9)  { width: 6%; }   /* Conveyance */
    .report-table colgroup col:nth-child(10) { width: 6%; }   /* Month */
    .report-table colgroup col:nth-child(11) { width: 5%; }   /* Absent Days */
    .report-table colgroup col:nth-child(12) { width: 7%; }   /* Absent Amount */
    .report-table colgroup col:nth-child(13) { width: 7%; }   /* Loan Deduction */
    .report-table colgroup col:nth-child(14) { width: 7%; }   /* Net Payable */
    .report-table colgroup col:nth-child(15) { width: 7%; }   /* Loan Amount */
    .report-table colgroup col:nth-child(16) { width: 7%; }   /* Monthly Deduct */
    .report-table colgroup col:nth-child(17) { width: 7%; }   /* Remaining Loan */

    /* Section group headers */
    .group-header {
        font-family: 'Space Mono', monospace;
        font-size: 0.5rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        padding: 0.4rem 0.6rem;
        text-align: center;
        border-bottom: 1px solid #1f2937;
    }
    .group-employee { color: #60a5fa; background: rgba(96,165,250,0.05); border-right: 1px solid #1f2937; }
    .group-payroll  { color: #f59e0b; background: rgba(245,158,11,0.05); border-right: 1px solid #1f2937; }
    .group-loan     { color: #f87171; background: rgba(248,113,113,0.05); }

    .report-table thead .col-header {
        font-family: 'Space Mono', monospace;
        font-size: 0.52rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 0.6rem 0.6rem;
        border-bottom: 2px solid #1f2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        background: rgba(12, 14, 18, 0.98);
    }
    .col-header.emp  { color: #93c5fd; }
    .col-header.pay  { color: #f59e0b; }
    .col-header.loan { color: #fca5a5; }

    .report-table tbody tr {
        border-bottom: 1px solid #0f1117;
        transition: background 0.2s ease;
    }
    .report-table tbody tr:hover { background: rgba(245, 158, 11, 0.03); }
    .report-table tbody tr:last-child { border-bottom: none; }

    .report-table tbody td {
        padding: 0.65rem 0.6rem;
        font-size: 0.75rem;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #9ca3af;
    }

    /* Cell types */
    .td-name        { font-weight: 700; color: #f9fafb; font-size: 0.8rem; }
    .td-date        { font-family: 'Space Mono', monospace; font-size: 0.65rem; color: #6b7280; }
    .td-money       { font-family: 'Space Mono', monospace; font-size: 0.68rem; color: #9ca3af; }
    .td-money-amber { font-family: 'Space Mono', monospace; font-size: 0.7rem; font-weight: 700; color: #f59e0b; }
    .td-money-green { font-family: 'Space Mono', monospace; font-size: 0.7rem; font-weight: 700; color: #34d399; }
    .td-money-red   { font-family: 'Space Mono', monospace; font-size: 0.68rem; color: #f87171; }
    .td-absent      { font-family: 'Space Mono', monospace; font-size: 0.7rem; text-align: center; }

    .badge-designation {
        display: inline-block;
        padding: 0.15rem 0.45rem;
        border-radius: 999px;
        font-size: 0.58rem;
        background: rgba(96,165,250,0.08);
        border: 1px solid rgba(96,165,250,0.2);
        color: #93c5fd;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .badge-month {
        display: inline-block;
        padding: 0.15rem 0.45rem;
        border-radius: 999px;
        font-size: 0.58rem;
        font-family: 'Space Mono', monospace;
        background: rgba(245,158,11,0.08);
        border: 1px solid rgba(245,158,11,0.2);
        color: #fbbf24;
        white-space: nowrap;
    }

    /* Section dividers in table */
    .border-section { border-right: 1px solid #1f2937 !important; }

    /* Summary footer cards */
    .summary-card {
        background: rgba(255,255,255,0.02);
        border: 1px solid #1f2937;
        border-radius: 0.75rem;
        padding: 0.85rem 1rem;
    }

    /* Hide print-only section on screen */
    .print-only-section {
        display: none;
        overflow: hidden;
        height: 0;
        position: absolute;
        left: -9999px;
    }

    /* ── Print Styles ── */
    @media print {
        @page {
            size: A4 landscape;
            margin: 8mm 7mm 8mm 7mm;
        }

        /* Hide screen-only elements */
        .no-print, .noise-overlay, .fixed, .accent-line { display: none !important; }
        .loan-col { display: none !important; }
        .border-section { border-right: none !important; }
        .card-glow { box-shadow: none !important; border: none !important; border-radius: 0 !important; background: #fff !important; }

        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, Helvetica, sans-serif !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .list-wrapper {
            background: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
            min-height: unset !important;
        }

        /* Print-only report header */
        .print-header {
            display: flex !important;
            align-items: flex-start !important;
            justify-content: space-between !important;
            border-bottom: 1.5px solid #000 !important;
            padding-bottom: 4pt !important;
            margin-bottom: 6pt !important;
        }
        .print-header h2 {
            font-family: Arial, sans-serif !important;
            font-size: 11pt !important;
            font-weight: bold !important;
            color: #000 !important;
            margin: 0 0 2pt 0 !important;
        }
        .print-header p {
            font-family: Arial, sans-serif !important;
            font-size: 7pt !important;
            color: #333 !important;
            margin: 0 !important;
        }
        .print-header .print-meta { text-align: right !important; }

        /* Table container — no overflow clipping */
        .table-outer {
            overflow: visible !important;
            width: 100% !important;
        }

        /* ── CRITICAL: auto layout so columns fill 100% width ── */
        .report-table {
            width: 100% !important;
            min-width: unset !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 6.5pt !important;
            border: none !important;
        }

        /* Force colgroup to reset — auto layout will distribute naturally */
        .report-table colgroup col { width: auto !important; }

        /* Group section headers */
        .group-header {
            font-family: Arial, sans-serif !important;
            font-size: 6.5pt !important;
            font-weight: bold !important;
            letter-spacing: 0.06em !important;
            padding: 3.5pt 4pt !important;
            text-align: center !important;
            border-top: 1px solid #999 !important;
            border-bottom: 1px solid #999 !important;
            border-left: none !important;
            border-right: none !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .group-employee {
            background: #e8f0fe !important;
            color: #1a3a8a !important;
        }
        .group-payroll {
            background: #fffaeb !important;
            color: #7c4a00 !important;
        }

        /* Column sub-headers */
        .col-header {
            font-family: Arial, sans-serif !important;
            font-size: 6pt !important;
            font-weight: bold !important;
            padding: 3pt 3pt !important;
            background: #f0f0f0 !important;
            color: #000 !important;
            border-top: 1px solid #aaa !important;
            border-bottom: 1.5px solid #888 !important;
            border-left: none !important;
            border-right: none !important;
            white-space: normal !important;
            word-break: break-word !important;
            text-align: center !important;
            line-height: 1.2 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Body cells — horizontal lines only, no vertical borders */
        .report-table tbody td {
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 6.5pt !important;
            color: #000000 !important;
            padding: 3pt 3pt !important;
            border-top: none !important;
            border-bottom: 1px solid #e2e2e2 !important;
            border-left: none !important;
            border-right: none !important;
            vertical-align: middle !important;
            white-space: normal !important;
            word-break: break-word !important;
            line-height: 1.3 !important;
        }

        /* Alternating rows */
        .report-table tbody tr:nth-child(even) td {
            background: #f6f6f6 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* All text black */
        .td-name        { font-weight: bold !important; color: #000 !important; font-size: 6.5pt !important; }
        .td-date        { color: #000 !important; font-size: 6pt !important; }
        .td-money       { color: #000 !important; font-size: 6.5pt !important; }
        .td-money-amber { color: #000 !important; font-size: 6.5pt !important; font-weight: bold !important; }
        .td-money-green { color: #000 !important; font-size: 6.5pt !important; font-weight: bold !important; }
        .td-money-red   { color: #000 !important; font-size: 6.5pt !important; }
        .td-absent      { color: #000 !important; font-size: 6.5pt !important; text-align: center !important; }

        /* Badges → plain text */
        .badge-designation, .badge-month {
            background: none !important;
            border: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
            color: #000 !important;
            font-size: 6.5pt !important;
            font-family: Arial, sans-serif !important;
            display: inline !important;
            max-width: none !important;
        }

        /* Absent badge → plain number */
        .td-absent span {
            background: none !important;
            border: none !important;
            color: #000 !important;
            display: inline !important;
            width: auto !important;
            height: auto !important;
            padding: 0 !important;
            border-radius: 0 !important;
            font-size: 6.5pt !important;
        }

        /* gray text → black */
        .text-gray-700, .mono.text-xs.text-gray-700 { color: #555 !important; }

        /* Pay Slip button → plain link text on print */
        .slip-btn {
            background: none !important;
            border: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
            color: #000 !important;
            font-size: 6.5pt !important;
            font-family: Arial, sans-serif !important;
            display: inline !important;
            text-decoration: underline !important;
        }

        /* Summary footer */
        .summary-footer-print {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            gap: 5pt !important;
            margin-top: 8pt !important;
            padding-top: 6pt !important;
            border-top: 1px solid #ccc !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            background: none !important;
            page-break-inside: avoid !important;
        }

        .summary-card {
            flex: 0 0 auto !important;
            width: 58mm !important;
            border: 1px solid #ccc !important;
            border-radius: 2pt !important;
            background: #f8f8f8 !important;
            padding: 4pt 6pt !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .summary-card p {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.4 !important;
        }

        .summary-card p:first-child {
            font-size: 5pt !important;
            color: #555 !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase !important;
            margin-bottom: 2pt !important;
        }

        .summary-card p:last-child {
            font-size: 8.5pt !important;
            font-weight: bold !important;
            color: #000 !important;
        }

        /* ALL rows must print */
        .report-table tbody tr {
            display: table-row !important;
            page-break-inside: avoid !important;
        }
        .report-table tbody { display: table-row-group !important; }
        thead { display: table-header-group !important; }
        tfoot { display: table-footer-group !important; }
        .report-table { page-break-inside: auto !important; }

        /* ── Screen table hidden on print ── */
        .no-print { display: none !important; }

        /* ── Print chunks: shown only on print ── */
        .print-only-section {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
            position: static !important;
            left: auto !important;
        }

        .print-chunk {
            display: block !important;
            width: 100% !important;
        }

        /* Each chunk except first starts on new page */
        .print-chunk.new-page {
            page-break-before: always !important;
            break-before: page !important;
        }

        /* No break inside a chunk */
        .print-chunk table {
            page-break-inside: auto !important;
        }
        .print-chunk tbody tr {
            page-break-inside: avoid !important;
        }
    }

    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>

<div class="list-wrapper min-h-screen bg-gray-950 p-4 md:p-8 lg:p-10">

    <div class="fixed inset-0 pointer-events-none opacity-5"
         style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="noise-overlay fixed inset-0"></div>

    <div class="relative w-full mx-auto">

        {{-- Page Header --}}
        <div class="no-print page-header flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div style="height:2px; width:2.5rem; background: linear-gradient(90deg, #f59e0b, #ef4444, transparent);"></div>
                    <span class="mono text-xs text-gray-500 tracking-widest">PAYROLL MANAGEMENT</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight" style="font-family: 'Syne', sans-serif;">
                    Full Payroll Report
                </h1>
                <p class="mono text-xs text-gray-600 mt-1 tracking-wide">
                    {{ count($employees) }} EMPLOYEE(S) &nbsp;·&nbsp; {{ $month }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- PDF Download --}}
                <a href="{{ route('report.pdf', array_filter(['month' => $month, 'year' => $year ?? null, 'employee_id' => $employee_id ?? null])) }}"
                   class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-gray-900 font-bold py-2.5 px-4 rounded-xl text-xs tracking-widest transition-all duration-200"
                   style="font-family: 'Space Mono', monospace;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('employee.list') }}"
                   class="flex items-center gap-2 border border-gray-700 hover:border-amber-500 hover:border-opacity-50 text-gray-400 hover:text-amber-400 font-semibold py-2.5 px-4 rounded-xl text-xs tracking-widest transition-all duration-200"
                   style="font-family: 'Space Mono', monospace;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    EMPLOYEES
                </a>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="no-print card-glow bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 mb-5">
            <div class="accent-line"></div>
            <div class="px-6 py-5">
                <p class="mono text-xs text-gray-600 tracking-widest mb-4">FILTER REPORT</p>
                <form method="GET" action="{{ route('report.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-5 items-end">

                    {{-- Employee --}}
                    <div>
                        <p class="mono text-xs text-gray-500 tracking-widest mb-2">EMPLOYEE</p>
                        <select name="employee_id" class="filter-select">
                            <option value="">All Employees</option>
                            @foreach(App\Models\Employee::all() as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Year --}}
                    <div>
                        <p class="mono text-xs text-gray-500 tracking-widest mb-2">YEAR</p>
                        <input type="number" name="year"
                               class="filter-input"
                               placeholder="e.g. 2026"
                               value="{{ request('year') }}">
                    </div>

                    {{-- Month --}}
                    <div>
                        <p class="mono text-xs text-gray-500 tracking-widest mb-2">MONTH</p>
                        <input type="month" name="month"
                               class="filter-input"
                               value="{{ $month }}">
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-2">
                        <button type="submit"
                                class="filter-btn flex-1 bg-amber-500 text-gray-900 font-bold py-2.5 px-4 rounded-xl text-sm tracking-wide flex items-center justify-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('report.index') }}"
                           class="flex items-center justify-center border border-gray-700 hover:border-gray-500 text-gray-500 hover:text-gray-300 rounded-xl px-3 transition-all duration-200"
                           title="Clear filters">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- Report Table --}}
        <div class="card-glow bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">

            <div class="accent-line"></div>

            {{-- Print-only header --}}
            <div class="print-header" style="display:none;">
                <div>
                    <h2>Full Payroll Report</h2>
                    <p>Period: {{ $month }} &nbsp;|&nbsp; {{ count($employees) }} Employee(s)</p>
                </div>
                <div class="print-meta">
                    <p>Printed: {{ now()->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════
                 SCREEN TABLE  (17 cols: Pay Slip first)
                 ═══════════════════════════════════════════════ --}}
            <div class="table-outer no-print">
                <table class="report-table">
                    <colgroup>
                        {{-- 1:Slip 2:Name 3:JoinDate 4:Designation 5:TotalSalary --}}
                        <col/><col/><col/><col/><col/>
                        {{-- 6:Basic 7:HouseRent 8:Medical 9:Conveyance --}}
                        <col/><col/><col/><col/>
                        {{-- 10:Month 11:Absent 12:AbsentAmt 13:LoanDeduct 14:NetPayable --}}
                        <col/><col/><col/><col/><col/>
                        {{-- 15:LoanAmt 16:Monthly 17:Remaining --}}
                        <col/><col/><col/>
                    </colgroup>
                    <thead>
                        {{-- Group row: Employee Info spans cols 1–9 (Pay Slip + 8 emp cols) --}}
                        <tr>
                            <th colspan="9" class="group-header group-employee">Employee Info</th>
                            <th colspan="5" class="group-header group-payroll">Payroll — {{ $month }}</th>
                            <th colspan="3" class="loan-col group-header group-loan">Loan Info</th>
                        </tr>
                        {{-- Column headers --}}
                        <tr>
                            <th class="col-header emp text-center">Pay Slip</th>
                            <th class="col-header emp">Name</th>
                            <th class="col-header emp">Join Date</th>
                            <th class="col-header emp">Designation</th>
                            <th class="col-header emp text-right">Total Salary</th>
                            <th class="col-header emp text-right">Basic</th>
                            <th class="col-header emp text-right">House Rent</th>
                            <th class="col-header emp text-right">Medical</th>
                            <th class="col-header emp text-right">Conveyance</th>
                            <th class="col-header pay text-center">Month</th>
                            <th class="col-header pay text-center">Absent</th>
                            <th class="col-header pay text-right">Absent Amt</th>
                            <th class="col-header pay text-right">Loan Deduct</th>
                            <th class="col-header pay text-right">Net Payable</th>
                            <th class="loan-col col-header loan text-right">Loan Amt</th>
                            <th class="loan-col col-header loan text-right">Monthly</th>
                            <th class="loan-col col-header loan text-right">Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        @php
                            $payroll = $employee->payrolls->first();
                            $loan    = $employee->loans->first();
                            $absent  = $payroll->absent_days ?? 0;
                        @endphp
                        <tr>
                            {{-- 1. Pay Slip --}}
                            <td class="text-center">
                                @if($payroll)
                                    <a href="{{ route('report.payslip', [$employee->id, $payroll->month]) }}"
                                       class="slip-btn inline-flex items-center px-3 py-1 rounded-md text-xs font-bold bg-emerald-500 hover:bg-emerald-400 text-gray-900 transition">
                                        Slip
                                    </a>
                                @else
                                    <span class="mono text-xs text-gray-700">—</span>
                                @endif
                            </td>
                            {{-- 2. Name --}}
                            <td class="td-name">{{ $employee->name }}</td>
                            {{-- 3. Join Date --}}
                            <td class="td-date">{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</td>
                            {{-- 4. Designation --}}
                            <td><span class="badge-designation">{{ $employee->designation }}</span></td>
                            {{-- 5. Total Salary --}}
                            <td class="td-money-amber text-right">৳{{ number_format($employee->total_salary, 2) }}</td>
                            {{-- 6. Basic --}}
                            <td class="td-money text-right">৳{{ number_format($employee->basic_salary, 2) }}</td>
                            {{-- 7. House Rent --}}
                            <td class="td-money text-right">৳{{ number_format($employee->house_rent, 2) }}</td>
                            {{-- 8. Medical --}}
                            <td class="td-money text-right">৳{{ number_format($employee->medical, 2) }}</td>
                            {{-- 9. Conveyance --}}
                            <td class="td-money text-right">৳{{ number_format($employee->conveyance, 2) }}</td>
                            {{-- 10. Month --}}
                            <td class="text-center">
                                @if($payroll)
                                    <span class="badge-month">{{ $payroll->month }}</span>
                                @else
                                    <span class="mono text-xs text-gray-700">—</span>
                                @endif
                            </td>
                            {{-- 11. Absent Days --}}
                            <td class="td-absent">
                                @if($absent > 0)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-md text-xs font-bold"
                                          style="background:rgba(248,113,113,0.12); border:1px solid rgba(248,113,113,0.25); color:#f87171;">
                                        {{ $absent }}
                                    </span>
                                @else
                                    <span class="mono text-xs text-gray-700">—</span>
                                @endif
                            </td>
                            {{-- 12. Absent Amount --}}
                            <td class="td-money-red text-right">
                                @if(($payroll->absent_amount ?? 0) > 0)
                                    ৳{{ number_format($payroll->absent_amount, 2) }}
                                @else
                                    <span class="text-gray-700">—</span>
                                @endif
                            </td>
                            {{-- 13. Loan Deduction --}}
                            <td class="td-money-red text-right">
                                @if(($payroll->loan_deduction ?? 0) > 0)
                                    ৳{{ number_format($payroll->loan_deduction, 2) }}
                                @else
                                    <span class="text-gray-700">—</span>
                                @endif
                            </td>
                            {{-- 14. Net Payable --}}
                            <td class="td-money-green text-right">
                                ৳{{ number_format($payroll->net_payable ?? $employee->total_salary, 2) }}
                            </td>
                            {{-- 15. Loan Amount --}}
                            <td class="loan-col td-money text-right">
                                @if(($loan->loan_amount ?? 0) > 0)
                                    ৳{{ number_format($loan->loan_amount, 2) }}
                                @else
                                    <span class="text-gray-700">—</span>
                                @endif
                            </td>
                            {{-- 16. Monthly Deduction --}}
                            <td class="loan-col td-money text-right">
                                @if(($loan->monthly_deduction ?? 0) > 0)
                                    ৳{{ number_format($loan->monthly_deduction, 2) }}
                                @else
                                    <span class="text-gray-700">—</span>
                                @endif
                            </td>
                            {{-- 17. Remaining Loan --}}
                            <td class="loan-col text-right">
                                @if(($loan->remaining_amount ?? 0) > 0)
                                    <span class="mono text-xs font-bold text-red-400">
                                        ৳{{ number_format($loan->remaining_amount, 2) }}
                                    </span>
                                @else
                                    <span class="mono text-xs text-emerald-500">CLEARED</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="17" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-800 border border-gray-700 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="mono text-xs text-gray-600 tracking-widest">NO PAYROLL DATA FOR THIS MONTH</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ═══════════════════════════════════════════════
                 PRINT CHUNKS  (same 17-col structure, no loan cols)
                 ═══════════════════════════════════════════════ --}}
            @php $chunks = $employees->chunk(10); @endphp
            <div class="print-only-section">
                @if($employees->isEmpty())
                <div class="print-chunk">
                    <table class="report-table">
                        <colgroup>
                            <col/><col/><col/><col/><col/>
                            <col/><col/><col/><col/>
                            <col/><col/><col/><col/><col/>
                            <col/><col/><col/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th colspan="9" class="group-header group-employee">Employee Info</th>
                                <th colspan="5" class="group-header group-payroll">Payroll — {{ $month }}</th>
                                <th colspan="3" class="loan-col group-header group-loan">Loan Info</th>
                            </tr>
                            <tr>
                                <th class="col-header emp text-center">Pay Slip</th>
                                <th class="col-header emp">Name</th>
                                <th class="col-header emp">Join Date</th>
                                <th class="col-header emp">Designation</th>
                                <th class="col-header emp text-right">Total Salary</th>
                                <th class="col-header emp text-right">Basic</th>
                                <th class="col-header emp text-right">House Rent</th>
                                <th class="col-header emp text-right">Medical</th>
                                <th class="col-header emp text-right">Conveyance</th>
                                <th class="col-header pay text-center">Month</th>
                                <th class="col-header pay text-center">Absent</th>
                                <th class="col-header pay text-right">Absent Amt</th>
                                <th class="col-header pay text-right">Loan Deduct</th>
                                <th class="col-header pay text-right">Net Payable</th>
                                <th class="loan-col col-header loan text-right">Loan Amt</th>
                                <th class="loan-col col-header loan text-right">Monthly</th>
                                <th class="loan-col col-header loan text-right">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="14" style="text-align:center; padding:20pt; color:#555;">No payroll data for this period.</td></tr>
                        </tbody>
                    </table>
                </div>
                @else
                @foreach($chunks as $chunkIndex => $chunk)
                <div class="print-chunk {{ $chunkIndex > 0 ? 'new-page' : '' }}">
                    <table class="report-table">
                        <colgroup>
                            <col/><col/><col/><col/><col/>
                            <col/><col/><col/><col/>
                            <col/><col/><col/><col/><col/>
                            <col/><col/><col/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th colspan="9" class="group-header group-employee">Employee Info</th>
                                <th colspan="5" class="group-header group-payroll">Payroll — {{ $month }}</th>
                                <th colspan="3" class="loan-col group-header group-loan">Loan Info</th>
                            </tr>
                            <tr>
                                <th class="col-header emp text-center">Pay Slip</th>
                                <th class="col-header emp">Name</th>
                                <th class="col-header emp">Join Date</th>
                                <th class="col-header emp">Designation</th>
                                <th class="col-header emp text-right">Total Salary</th>
                                <th class="col-header emp text-right">Basic</th>
                                <th class="col-header emp text-right">House Rent</th>
                                <th class="col-header emp text-right">Medical</th>
                                <th class="col-header emp text-right">Conveyance</th>
                                <th class="col-header pay text-center">Month</th>
                                <th class="col-header pay text-center">Absent</th>
                                <th class="col-header pay text-right">Absent Amt</th>
                                <th class="col-header pay text-right">Loan Deduct</th>
                                <th class="col-header pay text-right">Net Payable</th>
                                <th class="loan-col col-header loan text-right">Loan Amt</th>
                                <th class="loan-col col-header loan text-right">Monthly</th>
                                <th class="loan-col col-header loan text-right">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chunk as $employee)
                            @php
                                $payroll = $employee->payrolls->first();
                                $loan    = $employee->loans->first();
                                $absent  = $payroll->absent_days ?? 0;
                            @endphp
                            <tr>
                                <td class="text-center">
                                    @if($payroll)
                                        <a href="{{ route('report.payslip', [$employee->id, $payroll->month]) }}"
                                           class="slip-btn">Slip</a>
                                    @else
                                        <span class="mono text-xs text-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="td-name">{{ $employee->name }}</td>
                                <td class="td-date">{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</td>
                                <td><span class="badge-designation">{{ $employee->designation }}</span></td>
                                <td class="td-money-amber text-right">৳{{ number_format($employee->total_salary, 2) }}</td>
                                <td class="td-money text-right">৳{{ number_format($employee->basic_salary, 2) }}</td>
                                <td class="td-money text-right">৳{{ number_format($employee->house_rent, 2) }}</td>
                                <td class="td-money text-right">৳{{ number_format($employee->medical, 2) }}</td>
                                <td class="td-money text-right">৳{{ number_format($employee->conveyance, 2) }}</td>
                                <td class="text-center">
                                    @if($payroll)
                                        <span class="badge-month">{{ $payroll->month }}</span>
                                    @else
                                        <span class="mono text-xs text-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="td-absent">
                                    @if($absent > 0)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md text-xs font-bold"
                                              style="background:rgba(248,113,113,0.12); border:1px solid rgba(248,113,113,0.25); color:#f87171;">
                                            {{ $absent }}
                                        </span>
                                    @else
                                        <span class="mono text-xs text-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="td-money-red text-right">
                                    @if(($payroll->absent_amount ?? 0) > 0)
                                        ৳{{ number_format($payroll->absent_amount, 2) }}
                                    @else
                                        <span class="text-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="td-money-red text-right">
                                    @if(($payroll->loan_deduction ?? 0) > 0)
                                        ৳{{ number_format($payroll->loan_deduction, 2) }}
                                    @else
                                        <span class="text-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="td-money-green text-right">
                                    ৳{{ number_format($payroll->net_payable ?? $employee->total_salary, 2) }}
                                </td>
                                <td class="loan-col td-money text-right">
                                    @if(($loan->loan_amount ?? 0) > 0)
                                        ৳{{ number_format($loan->loan_amount, 2) }}
                                    @else
                                        <span class="text-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="loan-col td-money text-right">
                                    @if(($loan->monthly_deduction ?? 0) > 0)
                                        ৳{{ number_format($loan->monthly_deduction, 2) }}
                                    @else
                                        <span class="text-gray-700">—</span>
                                    @endif
                                </td>
                                <td class="loan-col text-right">
                                    @if(($loan->remaining_amount ?? 0) > 0)
                                        <span class="mono text-xs font-bold text-red-400">
                                            ৳{{ number_format($loan->remaining_amount, 2) }}
                                        </span>
                                    @else
                                        <span class="mono text-xs text-emerald-500">CLEARED</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
                @endif
            </div>

            {{-- Summary Footer --}}
            @if(count($employees) > 0)
            <div class="px-6 py-5 border-t border-gray-800 summary-footer-print">
                <p class="mono text-xs text-gray-600 tracking-widest mb-4">MONTH SUMMARY — {{ $month }}</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="summary-card">
                        <p class="mono text-xs text-gray-600 tracking-widest mb-1">TOTAL SALARY</p>
                        <p class="mono text-base font-bold text-amber-400">
                            ৳{{ number_format($employees->sum('total_salary'), 2) }}
                        </p>
                    </div>
                    <div class="summary-card">
                        <p class="mono text-xs text-gray-600 tracking-widest mb-1">NET PAYABLE</p>
                        <p class="mono text-base font-bold text-emerald-400">
                            ৳{{ number_format($employees->sum(fn($e) => $e->payrolls->first()->net_payable ?? $e->total_salary), 2) }}
                        </p>
                    </div>
                    <div class="summary-card">
                        <p class="mono text-xs text-gray-600 tracking-widest mb-1">LOAN DEDUCTIONS</p>
                        <p class="mono text-base font-bold text-red-400">
                            ৳{{ number_format($employees->sum(fn($e) => $e->payrolls->first()->loan_deduction ?? 0), 2) }}
                        </p>
                    </div>
                    <div class="summary-card">
                        <p class="mono text-xs text-gray-600 tracking-widest mb-1">TOTAL EMPLOYEES</p>
                        <p class="mono text-base font-bold text-blue-400">
                            {{ count($employees) }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Bottom label --}}
        <div class="no-print flex items-center gap-3 mt-6">
            <div style="flex:1; height:1px; background: linear-gradient(90deg, transparent, #374151);"></div>
            <span class="mono text-xs text-gray-700 tracking-widest">END OF REPORT</span>
            <div style="flex:1; height:1px; background: linear-gradient(90deg, #374151, transparent);"></div>
        </div>

    </div>
</div>

@endsection