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
        flex-wrap: wrap;
        gap: 1rem;
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
        transition: all 0.15s;
        cursor: pointer;
        border: 1px solid transparent;
        font-family: 'Inter', sans-serif;
    }
    .btn-outline {
        background: var(--surface);
        color: var(--text-primary);
        border-color: var(--border-md);
    }
    .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

    /* ── Filter Card ── */
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
        grid-template-columns: 1fr 1fr auto;
        gap: 1.25rem;
        align-items: end;
    }

    @media (max-width: 640px) { .filter-grid { grid-template-columns: 1fr; } }

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
    .filter-field input:focus { border-color: var(--accent); }

    input[type="month"]::-webkit-calendar-picker-indicator { opacity: 0.5; cursor: pointer; }

    .filter-actions { display: flex; gap: 0.5rem; }

    .btn-filter {
        flex: 1;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: 5px;
        padding: 0.5rem 1.2rem;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        font-family: 'Inter', sans-serif;
        transition: background 0.15s;
        white-space: nowrap;
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
        font-size: 0.72rem;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
    }

    .tbl-wrap { width: 100%; overflow-x: auto; }
    .tbl-wrap::-webkit-scrollbar { height: 4px; }
    .tbl-wrap::-webkit-scrollbar-track { background: var(--bg); }
    .tbl-wrap::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 2px; }

    .rpt-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    .rpt-table thead th {
        font-size: 0.62rem;
        font-weight: 700;
        color: var(--text-secondary);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.65rem 1.25rem;
        background: #f8f8f5;
        border-bottom: 2px solid var(--border-md);
        text-align: left;
        white-space: nowrap;
    }

    .rpt-table thead th.text-right { text-align: right; }
    .rpt-table thead th.text-center { text-align: center; }

    .rpt-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.1s;
    }
    .rpt-table tbody tr:hover { background: #f9f9f6; }
    .rpt-table tbody tr:last-child { border-bottom: none; }

    .rpt-table tbody td {
        padding: 0.75rem 1.25rem;
        vertical-align: middle;
        color: var(--text-secondary);
    }

    .c-num {
        font-family: 'DM Mono', monospace;
        font-size: 0.65rem;
        color: var(--text-muted);
        text-align: center;
    }

    .c-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.8rem;
    }

    .badge-mo {
        display: inline-block;
        padding: 0.18rem 0.6rem;
        border-radius: 4px;
        font-size: 0.62rem;
        font-family: 'DM Mono', monospace;
        background: var(--gold-lt);
        color: var(--gold);
    }

    .c-amount {
        font-family: 'DM Mono', monospace;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gold);
        text-align: right;
        font-feature-settings: "tnum";
    }

    .c-date {
        font-family: 'DM Mono', monospace;
        font-size: 0.65rem;
        color: var(--text-muted);
    }

    /* ── Empty state ── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; opacity: 0.3; }
    .empty-text { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; }

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
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        font-feature-settings: "tnum";
        letter-spacing: -0.02em;
    }

    .sum-value.v-gold  { color: var(--gold); }
    .sum-value.v-blue  { color: var(--accent); }

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

    /* ── Searchable Employee Dropdown ── */
    .emp-search-wrap {
        position: relative;
    }

    .emp-input-row {
        position: relative;
        display: flex;
        align-items: center;
    }

    .emp-search-input {
        width: 100%;
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s;
    }
    .emp-search-input:focus { border-color: var(--accent); background: var(--surface); }
    .emp-search-input::placeholder { color: var(--text-muted); }

    .emp-clear-btn {
        position: absolute;
        right: 0.5rem;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        padding: 0.2rem;
        border-radius: 3px;
        transition: color 0.12s;
    }
    .emp-clear-btn:hover { color: var(--red); }

    .emp-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: var(--surface);
        border: 1px solid var(--border-md);
        border-radius: 6px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        z-index: 100;
        max-height: 200px;
        overflow-y: auto;
    }
    .emp-dropdown::-webkit-scrollbar { width: 4px; }
    .emp-dropdown::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 2px; }

    .emp-option {
        padding: 0.5rem 0.85rem;
        font-size: 0.78rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: background 0.1s;
        border-bottom: 1px solid var(--border);
    }
    .emp-option:last-child { border-bottom: none; }
    .emp-option:hover { background: var(--accent-lt); color: var(--accent); }
    .emp-option.highlighted { background: var(--accent-lt); color: var(--accent); }
    .emp-option .match { font-weight: 700; color: var(--accent); }

    .emp-option.no-result {
        color: var(--text-muted);
        font-size: 0.72rem;
        text-align: center;
        cursor: default;
        font-style: italic;
    }
    .emp-option.no-result:hover { background: transparent; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .pw { padding: 1.25rem 1rem; }
        .corp-header { flex-direction: column; align-items: flex-start; }
        .summary-bar { grid-template-columns: 1fr; }
        .sum-item { border-right: none; border-bottom: 1px solid var(--border); }
        .sum-item:last-child { border-bottom: none; }
    }
</style>

<div class="pw">

    {{-- Page Header --}}
    <div class="corp-header">
        <div class="corp-header-left">
            <div class="eyebrow">Payroll Management System</div>
            <h1>Advance Report</h1>
            <div class="sub">
                {{ $month ? \Carbon\Carbon::parse($month . '-01')->format('F Y') : 'All Months' }}
                &nbsp;·&nbsp; {{ $advances->count() }} record(s)
            </div>
        </div>
        <div class="corp-header-right">
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
        <form method="GET" action="{{ request()->url() }}">
            <div class="filter-grid">
                <div class="filter-field">
                    <label>Month</label>
                    <input type="month" name="month" value="{{ $month }}">
                </div>
                <div class="filter-field">
                    <label>Employee</label>
                    <div class="emp-search-wrap" id="empSearchWrap">
                        <div class="emp-input-row">
                            <input type="text" id="empSearchInput" class="emp-search-input"
                                   placeholder="Type to search employee..."
                                   autocomplete="off"
                                   value="{{ request('employee_id') ? App\Models\Employee::find(request('employee_id'))?->name : '' }}">
                            <button type="button" class="emp-clear-btn" id="empClearBtn" style="{{ request('employee_id') ? '' : 'display:none;' }}">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <input type="hidden" name="employee_id" id="empHiddenId" value="{{ request('employee_id') }}">
                        <div class="emp-dropdown" id="empDropdown" style="display:none;">
                            @foreach(App\Models\Employee::orderBy('name')->get() as $emp)
                                <div class="emp-option" data-id="{{ $emp->id }}" data-name="{{ $emp->name }}">
                                    {{ $emp->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
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
                        <a href="{{ request()->url() }}" class="btn-clear" title="Clear">
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
            <span class="table-card-title">Advance Salary List</span>
            <span class="table-card-count">{{ $advances->count() }} records</span>
        </div>

        <div class="tbl-wrap">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Employee</th>
                        <th>Month</th>
                        <th class="text-right">Advance Amount</th>
                        <th>Taken Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advances as $key => $advance)
                    <tr>
                        <td class="c-num">{{ $key + 1 }}</td>
                        <td class="c-name">{{ $advance->employee->name }}</td>
                        <td>
                            <span class="badge-mo">
                                {{ \Carbon\Carbon::parse($advance->month . '-01')->format('M Y') }}
                            </span>
                        </td>
                        <td class="c-amount">৳{{ number_format($advance->amount, 2) }}</td>
                        <td class="c-date">
                            {{ \Carbon\Carbon::parse($advance->taken_date)->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="empty-text">No advance records found</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($advances->count() > 0)
        <div class="summary-bar">
            <div class="sum-item">
                <div class="sum-label">Total Advance</div>
                <div class="sum-value v-gold">৳{{ number_format($totalAdvance, 2) }}</div>
            </div>
            <div class="sum-item">
                <div class="sum-label">No. of Records</div>
                <div class="sum-value v-blue">{{ $advances->count() }}</div>
            </div>
            <div class="sum-item">
                <div class="sum-label">Avg. Per Record</div>
                <div class="sum-value">৳{{ number_format($advances->count() > 0 ? $totalAdvance / $advances->count() : 0, 2) }}</div>
            </div>
        </div>
        @endif

    </div>{{-- end .table-card --}}

    <div class="bottom-rule">End of Report</div>

</div>


<script>
(function () {
    const input      = document.getElementById('empSearchInput');
    const hidden     = document.getElementById('empHiddenId');
    const dropdown   = document.getElementById('empDropdown');
    const clearBtn   = document.getElementById('empClearBtn');
    const wrap       = document.getElementById('empSearchWrap');
    const allOptions = Array.from(dropdown.querySelectorAll('.emp-option'));

    let highlightIdx = -1;

    function showDropdown() { dropdown.style.display = 'block'; }
    function hideDropdown() { dropdown.style.display = 'none'; highlightIdx = -1; }

    function highlightOption(idx) {
        allOptions.forEach(o => o.classList.remove('highlighted'));
        const visible = allOptions.filter(o => o.style.display !== 'none');
        if (idx >= 0 && idx < visible.length) {
            visible[idx].classList.add('highlighted');
            visible[idx].scrollIntoView({ block: 'nearest' });
        }
        highlightIdx = idx;
    }

    function filterOptions(query) {
        const q = query.trim().toLowerCase();
        let visibleCount = 0;
        highlightIdx = -1;

        // Remove any existing no-result message
        const noRes = dropdown.querySelector('.no-result');
        if (noRes) noRes.remove();

        allOptions.forEach(opt => {
            const name = opt.dataset.name.toLowerCase();
            if (!q || name.includes(q)) {
                opt.style.display = 'block';
                // Highlight matching text
                if (q) {
                    const idx = opt.dataset.name.toLowerCase().indexOf(q);
                    const orig = opt.dataset.name;
                    opt.innerHTML = orig.substring(0, idx)
                        + '<span class="match">' + orig.substring(idx, idx + q.length) + '</span>'
                        + orig.substring(idx + q.length);
                } else {
                    opt.textContent = opt.dataset.name;
                }
                visibleCount++;
            } else {
                opt.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            const noResult = document.createElement('div');
            noResult.className = 'emp-option no-result';
            noResult.textContent = 'No employee found';
            dropdown.appendChild(noResult);
        }
    }

    function selectOption(id, name) {
        input.value  = name;
        hidden.value = id;
        clearBtn.style.display = 'flex';
        hideDropdown();
    }

    // Input events
    input.addEventListener('focus', () => {
        filterOptions(input.value);
        showDropdown();
    });

    input.addEventListener('input', () => {
        hidden.value = ''; // clear selection when typing
        clearBtn.style.display = input.value ? 'flex' : 'none';
        filterOptions(input.value);
        showDropdown();
    });

    // Keyboard navigation
    input.addEventListener('keydown', (e) => {
        const visible = allOptions.filter(o => o.style.display !== 'none' && !o.classList.contains('no-result'));
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            highlightOption(Math.min(highlightIdx + 1, visible.length - 1));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            highlightOption(Math.max(highlightIdx - 1, 0));
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (highlightIdx >= 0 && visible[highlightIdx]) {
                selectOption(visible[highlightIdx].dataset.id, visible[highlightIdx].dataset.name);
            }
        } else if (e.key === 'Escape') {
            hideDropdown();
        }
    });

    // Click on option
    allOptions.forEach(opt => {
        opt.addEventListener('mousedown', (e) => {
            e.preventDefault();
            selectOption(opt.dataset.id, opt.dataset.name);
        });
    });

    // Clear button
    clearBtn.addEventListener('click', () => {
        input.value  = '';
        hidden.value = '';
        clearBtn.style.display = 'none';
        filterOptions('');
        input.focus();
        showDropdown();
    });

    // Click outside → close
    document.addEventListener('click', (e) => {
        if (!wrap.contains(e.target)) hideDropdown();
    });
})();
</script>

@endsection