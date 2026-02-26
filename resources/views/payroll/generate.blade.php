@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap');

    .form-wrapper * { font-family: 'Syne', sans-serif; }
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

    .input-field {
        background: transparent;
        border: none;
        border-bottom: 2px solid #374151;
        border-radius: 0;
        transition: border-color 0.3s ease;
        color: #f9fafb;
        padding: 0.5rem 0;
        width: 100%;
        outline: none;
        font-size: 0.9rem;
        font-family: 'Space Mono', monospace;
    }
    /* Calendar icon color control */
    .input-field { color-scheme: dark; }
    
    .input-field:focus { border-bottom-color: #f59e0b; }
    .input-field::placeholder { color: #4b5563; }

    .select-field {
        background: transparent;
        border: none;
        border-bottom: 2px solid #374151;
        border-radius: 0;
        transition: border-color 0.3s ease;
        color: #f9fafb;
        padding: 0.5rem 0;
        width: 100%;
        outline: none;
        font-size: 0.9rem;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.25rem center;
        background-size: 1.1rem;
        padding-right: 1.75rem;
    }
    .select-field:focus { border-bottom-color: #f59e0b; }
    .select-field option { background: #1f2937; color: #f9fafb; }

    .label-text {
        font-size: 0.6rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #f59e0b;
        font-weight: 600;
        font-family: 'Space Mono', monospace;
    }

    .step-number {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        color: #374151;
    }

    /* Month quick-select pills */
    .month-pill {
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        border: 1px solid #374151;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .month-pill:hover, .month-pill.active {
        border-color: rgba(245,158,11,0.5);
        color: #fbbf24;
        background: rgba(245,158,11,0.06);
    }

    /* Absent day stepper */
    .stepper-btn {
        width: 2rem; height: 2rem;
        border-radius: 0.5rem;
        border: 1px solid #374151;
        color: #9ca3af;
        background: transparent;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stepper-btn:hover {
        border-color: rgba(245,158,11,0.5);
        color: #f59e0b;
        background: rgba(245,158,11,0.06);
    }
    .stepper-display {
        font-family: 'Space Mono', monospace;
        font-size: 1.4rem;
        font-weight: 700;
        color: #f9fafb;
        min-width: 3rem;
        text-align: center;
        background: transparent;
        border: none;
        outline: none;
    }
    .stepper-display.has-absent { color: #f87171; }

    .submit-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
        transition: left 0.5s ease;
    }
    .submit-btn:hover::before { left: 100%; }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        background-color: #fbbf24;
    }

    .back-btn { transition: all 0.2s ease; }
    .back-btn:hover {
        border-color: #6b7280;
        color: #d1d5db;
        transform: translateY(-1px);
    }

    .preview-box {
        background: rgba(245, 158, 11, 0.04);
        border: 1px solid rgba(245, 158, 11, 0.12);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
    }
</style>

<div class="form-wrapper min-h-screen bg-gray-950 flex items-center justify-center p-6">

    <div class="fixed inset-0 pointer-events-none opacity-5"
         style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="noise-overlay fixed inset-0"></div>

    <div class="relative w-full max-w-lg">

        {{-- Top strip --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="accent-line flex-1"></div>
            <span class="mono text-xs text-gray-500 tracking-widest">PAYROLL MANAGEMENT</span>
            <div class="accent-line flex-1"></div>
        </div>

        {{-- Card --}}
        <div class="card-glow relative bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">

            <div class="accent-line"></div>

            {{-- Header --}}
            <div class="px-8 pt-8 pb-6 flex items-start justify-between border-b border-gray-800">
                <div>
                    <p class="label-text mb-1">New Record</p>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight" style="font-family: 'Syne', sans-serif;">
                        Generate Salary
                    </h1>
                    <p class="mono text-xs text-gray-600 mt-1 tracking-wide">MONTHLY PAYROLL</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('employee.list') }}"
                       class="flex items-center gap-2 border border-gray-700 hover:border-amber-500 hover:border-opacity-50 text-gray-400 hover:text-amber-400 font-semibold py-2 px-4 rounded-xl text-xs tracking-widest transition-all duration-200"
                       style="font-family: 'Space Mono', monospace;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        EMPLOYEES
                    </a>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         style="background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.2);">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-8 py-8">
                <form action="{{ route('payroll.generate') }}" method="POST">
                    @csrf

                    <div class="space-y-8">

                        {{-- Employee --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Employee</label>
                                <span class="step-number">01</span>
                            </div>
                            <select name="employee_id" class="select-field" required>
                                <option value="" disabled selected>Select an employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Month (Updated to type="month") --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Month</label>
                                <span class="step-number">02</span>
                            </div>
                            <input type="month" name="month" id="month_input"
                                   class="input-field" required>
                            {{-- Quick select pills --}}
                            <div class="flex flex-wrap gap-2 mt-3" id="month-pills"></div>
                        </div>

                        {{-- Absent Days --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Absent Days</label>
                                <span class="step-number">03</span>
                            </div>
                            {{-- Hidden input for form submission --}}
                            <input type="hidden" name="absent_days" id="absent_days_value" value="0">
                            <div class="flex items-center gap-4 mt-1">
                                <button type="button" class="stepper-btn" id="btn-minus">−</button>
                                <input type="text" id="absent_display" class="stepper-display" value="0" readonly>
                                <button type="button" class="stepper-btn" id="btn-plus">+</button>
                                <span class="mono text-xs text-gray-600 tracking-widest ml-2">DAY(S)</span>
                            </div>
                        </div>

                    </div>

                    {{-- Preview --}}
                    <div class="preview-box mt-8">
                        <p class="label-text mb-3">Payroll Preview</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="mono text-xs text-gray-600 tracking-widest mb-1">PERIOD</p>
                                <p class="mono text-sm font-bold text-amber-400" id="preview-month">—</p>
                            </div>
                            <div class="w-px h-8 bg-gray-800"></div>
                            <div>
                                <p class="mono text-xs text-gray-600 tracking-widest mb-1">EMPLOYEE</p>
                                <p class="mono text-sm font-bold text-white" id="preview-employee">—</p>
                            </div>
                            <div class="w-px h-8 bg-gray-800"></div>
                            <div class="text-right">
                                <p class="mono text-xs text-gray-600 tracking-widest mb-1">ABSENT</p>
                                <p class="mono text-sm font-bold" id="preview-absent">0 day(s)</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 my-8"></div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="submit-btn flex-1 bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold py-3.5 px-6 rounded-xl tracking-wide text-sm">
                            Generate Salary
                        </button>
                        <a href="{{ route('employee.list') }}"
                           class="back-btn flex items-center gap-2 border border-gray-700 text-gray-400 font-semibold py-3.5 px-5 rounded-xl text-sm tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                    </div>

                </form>
            </div>

            {{-- Corner decoration --}}
            <div class="absolute bottom-0 right-0 w-24 h-24 pointer-events-none opacity-5">
                <svg viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="60" stroke="#f59e0b" stroke-width="1"/>
                    <circle cx="100" cy="100" r="40" stroke="#f59e0b" stroke-width="1"/>
                    <circle cx="100" cy="100" r="20" stroke="#f59e0b" stroke-width="1"/>
                </svg>
            </div>

        </div>

        <p class="mono text-center text-xs text-gray-700 mt-5 tracking-widest">ALL FIELDS REQUIRED</p>

    </div>
</div>

<script>
    // ── Month quick-select pills ──
    const monthInput   = document.getElementById('month_input');
    const pillsWrapper = document.getElementById('month-pills');
    const previewMonth = document.getElementById('preview-month');

    function formatPreviewDate(value) {
        if (!value) return '—';
        const [year, month] = value.split('-');
        const date = new Date(year, month - 1);
        return date.toLocaleString('default', { month: 'short' }).toUpperCase() + ' ' + year;
    }

    const now = new Date();
    for (let i = 0; i < 4; i++) {
        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const val = `${year}-${month}`;
        
        const label = d.toLocaleString('default', { month: 'short' }) + ' ' + year;
        const pill = document.createElement('button');
        pill.type = 'button';
        pill.className = 'month-pill' + (i === 0 ? ' active' : '');
        pill.textContent = label;
        pill.dataset.value = val;

        if (i === 0) { 
            monthInput.value = val; 
            previewMonth.textContent = formatPreviewDate(val); 
        }

        pill.addEventListener('click', () => {
            document.querySelectorAll('.month-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            monthInput.value = val;
            previewMonth.textContent = formatPreviewDate(val);
        });
        pillsWrapper.appendChild(pill);
    }

    // Update preview when calendar input changes
    monthInput.addEventListener('input', () => {
        document.querySelectorAll('.month-pill').forEach(p => p.classList.remove('active'));
        previewMonth.textContent = formatPreviewDate(monthInput.value);
    });

    // ── Absent days stepper ──
    let absentDays = 0;
    const display    = document.getElementById('absent_display');
    const hidden     = document.getElementById('absent_days_value');
    const previewAbs = document.getElementById('preview-absent');

    function updateAbsent() {
        display.value  = absentDays;
        hidden.value   = absentDays;
        display.className = 'stepper-display' + (absentDays > 0 ? ' has-absent' : '');
        previewAbs.textContent  = absentDays + ' day(s)';
        previewAbs.style.color  = absentDays > 0 ? '#f87171' : '#34d399';
    }

    document.getElementById('btn-plus').addEventListener('click',  () => { absentDays++; updateAbsent(); });
    document.getElementById('btn-minus').addEventListener('click', () => { if (absentDays > 0) { absentDays--; updateAbsent(); } });

    // ── Employee preview ──
    const empSelect      = document.querySelector('select[name="employee_id"]');
    const previewEmployee = document.getElementById('preview-employee');
    empSelect.addEventListener('change', () => {
        previewEmployee.textContent = empSelect.options[empSelect.selectedIndex].text || '—';
    });

    updateAbsent();
</script>

@endsection