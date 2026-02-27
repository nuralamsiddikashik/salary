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

    /* Flash messages */
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
    .flash-success {
        background: var(--green-lt);
        border: 1px solid #b6d9c5;
        color: var(--green);
    }
    .flash-error {
        background: var(--red-lt);
        border: 1px solid #e8c5c5;
        color: var(--red);
    }
    .flash svg { flex-shrink: 0; }

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

    /* Stepper */
    .stepper-wrap { display: flex; align-items: center; gap: 0.75rem; margin-top: 0.25rem; }

    .stepper-btn {
        width: 2rem;
        height: 2rem;
        border-radius: 6px;
        border: 1px solid var(--border-md);
        background: var(--bg);
        color: var(--text-secondary);
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s;
        font-family: 'DM Sans', sans-serif;
    }
    .stepper-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }

    .stepper-display {
        font-family: 'DM Mono', monospace;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
        min-width: 2.5rem;
        text-align: center;
        background: transparent;
        border: none;
        outline: none;
    }
    .stepper-display.has-absent { color: var(--red); }

    .stepper-unit {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

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

    .preview-grid { display: flex; align-items: center; justify-content: space-between; gap: 0.35rem; }

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
        font-size: 0.82rem;
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

        {{-- Flash Messages --}}
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
            <a href="{{ route('employee.list') }}">Employees</a>
            <span class="sep">></span>
            <span class="current">Generate Salary</span>
        </div>

        <div class="form-card">

            <div class="card-head">
                <div>
                    <div class="eyebrow">Payroll Management</div>
                    <h1>Generate Salary</h1>
                </div>
                <a href="{{ route('employee.list') }}" class="btn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Employees
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('payroll.generate') }}" method="POST">
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
                                            data-total-leave="{{ $employee->total_leave ?? 0 }}"
                                            data-used-leave="{{ $employee->used_leave ?? 0 }}">
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
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

                        {{-- 03: Absent Days --}}
                        <div class="field">
                            <div class="field-row">
                                <label>Absent Days</label>
                                <span class="field-num">03</span>
                            </div>
                            <input type="hidden" name="absent_days" id="absent_days_value" value="0">
                            <div class="stepper-wrap">
                                <button type="button" class="stepper-btn" id="btn-minus">&#8722;</button>
                                <input type="text" id="absent_display" class="stepper-display" value="0" readonly>
                                <button type="button" class="stepper-btn" id="btn-plus">+</button>
                                <span class="stepper-unit">Day(s)</span>
                            </div>
                        </div>

                        {{-- 04: Leave Days --}}
                        {{--
                            Controller logic:
                            - leave_days <= absent_days হতে হবে
                            - leave_days <= employee.remaining_leave হতে হবে
                            - Leave কাটলে সেই দিনের salary deduct হবে না
                        --}}
                        <div class="field">
                            <div class="field-row">
                                <label for="leave_days">Leave Days</label>
                                <span class="field-num">04</span>
                            </div>
                            <input type="number" name="leave_days" id="leave_days"
                                   value="{{ old('leave_days', 0) }}" min="0" placeholder="0">
                            <div class="field-hint">
                                Remaining leave:
                                <span class="available" id="remaining_leave">—</span>
                                <span id="leave_warn" class="warn" style="display:none;">
                                    Exceeds limit!
                                </span>
                            </div>
                        </div>

                    </div>

                    {{-- Preview --}}
                    <div class="preview-box">
                        <div class="preview-label">Payroll Preview</div>
                        <div class="preview-grid">
                            <div class="preview-item">
                                <div class="preview-item-label">Period</div>
                                <div class="preview-item-value" id="preview-month">—</div>
                            </div>
                            <div class="preview-divider"></div>
                            <div class="preview-item">
                                <div class="preview-item-label">Employee</div>
                                <div class="preview-item-value" id="preview-employee">—</div>
                            </div>
                            <div class="preview-divider"></div>
                            <div class="preview-item">
                                <div class="preview-item-label">Absent</div>
                                <div class="preview-item-value" id="preview-absent">0 day(s)</div>
                            </div>
                            <div class="preview-divider"></div>
                            <div class="preview-item">
                                <div class="preview-item-label">Leave</div>
                                <div class="preview-item-value" id="preview-leave">0 day(s)</div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="actions">
                        <button type="submit" class="btn-submit">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Generate Salary
                        </button>
                        <a href="{{ route('employee.list') }}" class="btn-back">
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
        pill.type        = 'button';
        pill.className   = 'month-pill' + (i === 0 ? ' active' : '');
        pill.textContent = label;
        pill.dataset.value = val;

        if (i === 0) {
            monthInput.value = val;
            previewMonth.textContent = formatMonth(val);
        }

        pill.addEventListener('click', () => {
            document.querySelectorAll('.month-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            monthInput.value = val;
            previewMonth.textContent = formatMonth(val);
        });

        pillsWrapper.appendChild(pill);
    }

    monthInput.addEventListener('input', () => {
        document.querySelectorAll('.month-pill').forEach(p => p.classList.remove('active'));
        previewMonth.textContent = formatMonth(monthInput.value);
    });

    // ── Absent stepper ──
    let absentDays   = 0;
    const display    = document.getElementById('absent_display');
    const hidden     = document.getElementById('absent_days_value');
    const previewAbs = document.getElementById('preview-absent');

    function updateAbsent() {
        display.value     = absentDays;
        hidden.value      = absentDays;
        display.className = 'stepper-display' + (absentDays > 0 ? ' has-absent' : '');
        previewAbs.textContent = absentDays + ' day(s)';
        previewAbs.style.color = absentDays > 0 ? 'var(--red)' : 'var(--green)';

        // Leave cannot exceed absent — revalidate
        validateLeave();
    }

    document.getElementById('btn-plus').addEventListener('click',  () => { absentDays++; updateAbsent(); });
    document.getElementById('btn-minus').addEventListener('click', () => { if (absentDays > 0) { absentDays--; updateAbsent(); } });

    // ── Employee select → leave data ──
    const empSelect      = document.getElementById('employee_id');
    const previewEmp     = document.getElementById('preview-employee');
    const remainingLeave = document.getElementById('remaining_leave');
    const leaveWarn      = document.getElementById('leave_warn');
    const leaveInput     = document.getElementById('leave_days');
    const previewLeave   = document.getElementById('preview-leave');

    let maxLeave = 0; // employee remaining leave

    empSelect.addEventListener('change', function () {
        const selected  = empSelect.options[empSelect.selectedIndex];
        previewEmp.textContent = selected.text || '—';

        const totalLeave = parseInt(selected.getAttribute('data-total-leave')) || 0;
        const usedLeave  = parseInt(selected.getAttribute('data-used-leave'))  || 0;
        maxLeave = Math.max(0, totalLeave - usedLeave);

        remainingLeave.textContent = maxLeave + ' day(s)';

        // Reset leave input on employee change
        leaveInput.value = 0;
        previewLeave.textContent = '0 day(s)';
        previewLeave.style.color = '';
        leaveWarn.style.display  = 'none';
        leaveInput.style.borderColor = '';
    });

    // ── Leave days validation & preview ──
    function validateLeave() {
        const val  = parseInt(leaveInput.value) || 0;
        const limit = Math.min(maxLeave, absentDays); // cannot exceed either

        previewLeave.textContent = val + ' day(s)';
        previewLeave.style.color = val > 0 ? 'var(--gold)' : '';

        if (val > limit) {
            leaveWarn.style.display  = 'inline';
            leaveInput.style.borderColor = 'var(--red)';
        } else {
            leaveWarn.style.display  = 'none';
            leaveInput.style.borderColor = '';
        }
    }

    leaveInput.addEventListener('input', validateLeave);

    updateAbsent();
</script>

@endsection