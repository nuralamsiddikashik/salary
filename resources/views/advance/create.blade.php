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

    * { box-sizing: border-box; margin: 0; padding: 0; }
    .fw * { font-family: 'DM Sans', sans-serif; }

    .fw {
        min-height: 100vh;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .form-shell { width: 100%; max-width: 500px; }

    /* Flash */
    .flash {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.7rem 1rem;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    .flash-success { background: var(--green-lt); border: 1px solid #b6d9c5; color: var(--green); }
    .flash-error   { background: var(--red-lt);   border: 1px solid #e8c5c5; color: var(--red); }
    .flash svg { flex-shrink: 0; }

    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
        font-size: 0.68rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    .breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color 0.15s; }
    .breadcrumb a:hover { color: var(--accent); }
    .breadcrumb .sep { opacity: 0.4; }
    .breadcrumb .current { color: var(--text-primary); font-weight: 600; }

    /* Card */
    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.4rem 1.75rem;
        border-bottom: 1px solid var(--border);
    }
    .card-head .eyebrow {
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
        margin-bottom: 0.3rem;
    }
    .card-head h1 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.9rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-secondary);
        border: 1px solid var(--border-md);
        background: var(--bg);
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }

    .card-body { padding: 1.75rem; }

    .field-group { display: flex; flex-direction: column; gap: 1.4rem; }

    .field { display: flex; flex-direction: column; gap: 0.4rem; }

    .field-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .field label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-secondary);
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .field-num {
        font-family: 'DM Mono', monospace;
        font-size: 0.6rem;
        color: var(--border-md);
    }

    .field input,
    .field select {
        width: 100%;
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 6px;
        color: var(--text-primary);
        padding: 0.6rem 0.85rem;
        font-size: 0.85rem;
        font-family: 'DM Sans', sans-serif;
        outline: none;
        transition: border-color 0.15s, background 0.15s;
    }
    .field input:focus,
    .field select:focus { border-color: var(--accent); background: var(--surface); }
    .field input::placeholder { color: var(--text-muted); font-size: 0.82rem; }

    .field select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%235a5a54'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 0.9rem;
        padding-right: 2.2rem;
        cursor: pointer;
    }
    .field select option { background: #fff; color: var(--text-primary); }

    input[type="month"] { color-scheme: light; }
    input[type="month"]::-webkit-calendar-picker-indicator { opacity: 0.4; cursor: pointer; }
    input[type="month"]::-webkit-calendar-picker-indicator:hover { opacity: 0.8; }

    /* Taka prefix input */
    .input-wrap { position: relative; }
    .input-wrap .currency {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        font-family: 'DM Mono', monospace;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--gold);
        pointer-events: none;
    }
    .input-wrap input { padding-left: 1.9rem; }

    /* Field hint */
    .field-hint {
        font-size: 0.65rem;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
        margin-top: 0.2rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .field-hint .available { font-weight: 600; color: var(--green); }
    .field-hint .warn      { color: var(--red); }

    /* Month pills */
    .pill-row { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem; }

    .month-pill {
        font-family: 'DM Mono', monospace;
        font-size: 0.65rem;
        font-weight: 500;
        padding: 0.25rem 0.65rem;
        border-radius: 4px;
        border: 1px solid var(--border-md);
        color: var(--text-muted);
        background: var(--bg);
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .month-pill:hover,
    .month-pill.active { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }

    /* Preview box */
    .preview-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-top: 1.5rem;
    }

    .preview-label {
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.85rem;
    }

    .preview-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.35rem;
    }

    .preview-item { flex: 1; text-align: center; }

    .preview-item-label {
        font-size: 0.58rem;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .preview-item-value {
        font-family: 'DM Mono', monospace;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .preview-divider { width: 1px; height: 2rem; background: var(--border-md); flex-shrink: 0; }

    .divider { height: 1px; background: var(--border); margin: 1.5rem 0; }

    .actions { display: flex; gap: 0.75rem; }

    .btn-submit {
        flex: 1;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: 6px;
        padding: 0.7rem 1.25rem;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        transition: background 0.15s, transform 0.15s;
    }
    .btn-submit:hover { background: #142d47; transform: translateY(-1px); }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.7rem 1.1rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-secondary);
        border: 1px solid var(--border-md);
        background: var(--surface);
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-back:hover { color: var(--text-primary); background: var(--bg); }

    .form-note {
        text-align: center;
        font-size: 0.65rem;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        font-family: 'DM Mono', monospace;
        margin-top: 1rem;
    }
</style>

<div class="fw">
    <div class="form-shell">

        {{-- Flash --}}
        @if(session('success'))
        <div class="flash flash-success">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="flash flash-error">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="breadcrumb">
            <a href="{{ route('advance.index') }}">Advance Salary List</a>
            <span class="sep">></span>
            <span class="current">Advance Salary</span>
        </div>

        <div class="form-card">

            <div class="card-head">
                <div>
                    <div class="eyebrow">Payroll Management</div>
                    <h1>Advance Salary</h1>
                </div>
                <a href="{{ route('advance.index') }}" class="btn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Advance Salary List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('advance.store') }}" method="POST">
                    @csrf

                    <div class="field-group">

                        {{-- 01: Employee --}}
                        <div class="field">
                            <div class="field-row">
                                <label for="employee_id">Employee</label>
                                <span class="field-num">01</span>
                            </div>
                            <select name="employee_id" id="employee_id" required>
                                <option value="" disabled selected>Select an employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                            data-salary="{{ $employee->total_salary }}">
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="field-hint">
                                Total salary:
                                <span class="available" id="emp_salary">—</span>
                            </div>
                        </div>

                        {{-- 02: Month --}}
                        <div class="field">
                            <div class="field-row">
                                <label for="month_input">Month</label>
                                <span class="field-num">02</span>
                            </div>
                            <input type="month" name="month" id="month_input" required>
                            <div class="pill-row" id="month-pills"></div>
                        </div>

                        {{-- 03: Advance Amount --}}
                        <div class="field">
                            <div class="field-row">
                                <label for="amount">Advance Amount</label>
                                <span class="field-num">03</span>
                            </div>
                            <div class="input-wrap">
                                <span class="currency">৳</span>
                                <input type="number" name="amount" id="amount"
                                       placeholder="0.00" min="0" step="0.01" required>
                            </div>
                            <div class="field-hint">
                                <span id="amount_warn" class="warn" style="display:none;">
                                    Exceeds total salary!
                                </span>
                            </div>
                        </div>

                    </div>

                    {{-- Preview --}}
                    <div class="preview-box">
                        <div class="preview-label">Advance Summary</div>
                        <div class="preview-grid">
                            <div class="preview-item">
                                <div class="preview-item-label">Employee</div>
                                <div class="preview-item-value" id="preview-employee">—</div>
                            </div>
                            <div class="preview-divider"></div>
                            <div class="preview-item">
                                <div class="preview-item-label">Period</div>
                                <div class="preview-item-value" id="preview-month">—</div>
                            </div>
                            <div class="preview-divider"></div>
                            <div class="preview-item">
                                <div class="preview-item-label">Total Salary</div>
                                <div class="preview-item-value" id="preview-salary" style="color:var(--green);">—</div>
                            </div>
                            <div class="preview-divider"></div>
                            <div class="preview-item">
                                <div class="preview-item-label">Advance</div>
                                <div class="preview-item-value" id="preview-advance" style="color:var(--gold);">৳0</div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="actions">
                        <button type="submit" class="btn-submit">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Save Advance Salary
                        </button>
                        <a href="{{ route('advance.index') }}" class="btn-back">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                    </div>

                </form>
            </div>

        </div>

        <p class="form-note">All fields are required</p>

    </div>
</div>

<script>
    // ── Month pills ──
    const monthInput   = document.getElementById('month_input');
    const pillsWrapper = document.getElementById('month-pills');
    const previewMonth = document.getElementById('preview-month');

    function formatMonth(value) {
        if (!value) return '—';
        const [year, month] = value.split('-');
        const d = new Date(year, month - 1);
        return d.toLocaleString('default', { month: 'short' }).toUpperCase() + ' ' + year;
    }

    const now = new Date();
    for (let i = 0; i < 4; i++) {
        const d     = new Date(now.getFullYear(), now.getMonth() - i, 1);
        const year  = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const val   = `${year}-${month}`;
        const label = d.toLocaleString('default', { month: 'short' }) + ' ' + year;

        const pill = document.createElement('button');
        pill.type          = 'button';
        pill.className     = 'month-pill' + (i === 0 ? ' active' : '');
        pill.textContent   = label;
        pill.dataset.value = val;

        if (i === 0) {
            monthInput.value         = val;
            previewMonth.textContent = formatMonth(val);
        }

        pill.addEventListener('click', () => {
            document.querySelectorAll('.month-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            monthInput.value         = val;
            previewMonth.textContent = formatMonth(val);
        });

        pillsWrapper.appendChild(pill);
    }

    monthInput.addEventListener('input', () => {
        document.querySelectorAll('.month-pill').forEach(p => p.classList.remove('active'));
        previewMonth.textContent = formatMonth(monthInput.value);
    });

    // ── Employee select ──
    const empSelect      = document.getElementById('employee_id');
    const empSalaryHint  = document.getElementById('emp_salary');
    const previewEmp     = document.getElementById('preview-employee');
    const previewSalary  = document.getElementById('preview-salary');
    const amountInput    = document.getElementById('amount');
    const amountWarn     = document.getElementById('amount_warn');
    const previewAdvance = document.getElementById('preview-advance');

    let maxSalary = 0;

    function fmt(val) {
        return '৳' + parseFloat(val || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    }

    empSelect.addEventListener('change', function () {
        const selected = empSelect.options[empSelect.selectedIndex];
        maxSalary = parseFloat(selected.getAttribute('data-salary')) || 0;

        previewEmp.textContent    = selected.text || '—';
        empSalaryHint.textContent = fmt(maxSalary);
        previewSalary.textContent = fmt(maxSalary);

        // re-validate amount
        validateAmount();
    });

    // ── Amount validation & preview ──
    function validateAmount() {
        const val = parseFloat(amountInput.value) || 0;
        previewAdvance.textContent = fmt(val);

        if (maxSalary > 0 && val > maxSalary) {
            amountWarn.style.display      = 'inline';
            amountInput.style.borderColor = 'var(--red)';
            previewAdvance.style.color    = 'var(--red)';
        } else {
            amountWarn.style.display      = 'none';
            amountInput.style.borderColor = '';
            previewAdvance.style.color    = 'var(--gold)';
        }
    }

    amountInput.addEventListener('input', validateAmount);
</script>

@endsection