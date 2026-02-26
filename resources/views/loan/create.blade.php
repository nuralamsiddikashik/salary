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

    /* Inputs */
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
    }
    .input-field:focus { border-bottom-color: #f59e0b; }
    .input-field::placeholder { color: #4b5563; }

    /* Select styling */
    .select-field {
        background: #111827;
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
    .select-field option {
        background: #1f2937;
        color: #f9fafb;
        padding: 0.5rem;
    }

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

    .back-btn:hover {
        border-color: #6b7280;
        color: #d1d5db;
        transform: translateY(-1px);
    }

    .list-btn:hover {
        border-color: rgba(245,158,11,0.5);
        color: #fbbf24;
    }

    /* Loan summary preview box */
    .preview-box {
        background: rgba(245, 158, 11, 0.04);
        border: 1px solid rgba(245, 158, 11, 0.12);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        transition: all 0.3s ease;
    }
</style>

<div class="form-wrapper min-h-screen bg-gray-950 flex items-center justify-center p-6">

    {{-- Background grid --}}
    <div class="fixed inset-0 pointer-events-none opacity-5"
         style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="noise-overlay fixed inset-0"></div>

    <div class="relative w-full max-w-lg">

        {{-- Top strip --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="accent-line flex-1"></div>
            <span class="mono text-xs text-gray-500 tracking-widest">LOAN MANAGEMENT</span>
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
                        Create Loan
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('employee.list') }}"
                       class="list-btn flex items-center gap-2 border border-gray-700 text-gray-400 font-semibold py-2 px-4 rounded-xl text-xs tracking-widest transition-all duration-200"
                       style="font-family: 'Space Mono', monospace;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        EMPLOYEES
                    </a>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         style="background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.2);">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-8 py-8">
                <form action="{{ route('loan.store') }}" method="POST">
                    @csrf

                    <div class="space-y-8">

                        {{-- Employee --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Employee</label>
                                <span class="step-number">01</span>
                            </div>
                            <select name="employee_id" class="select-field" required>
                                <option value="" disabled selected style="color:#4b5563;">Select an employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Loan Amount --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Loan Amount</label>
                                <span class="step-number">02</span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-0 bottom-2 text-amber-500 font-bold mono text-sm">৳</span>
                                <input type="number" name="loan_amount" id="loan_amount"
                                       class="input-field pl-5" placeholder="0.00" min="0" required>
                            </div>
                        </div>

                        {{-- Monthly Deduction --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Monthly Deduction</label>
                                <span class="step-number">03</span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-0 bottom-2 text-amber-500 font-bold mono text-sm">৳</span>
                                <input type="number" name="monthly_deduction" id="monthly_deduction"
                                       class="input-field pl-5" placeholder="0.00" min="0" required>
                            </div>
                        </div>

                    </div>

                    {{-- Live Summary Preview --}}
                    <div class="preview-box mt-8" id="summary-box">
                        <p class="label-text mb-3">Loan Summary</p>
                        <div class="flex items-center justify-between">
                            <div class="text-center">
                                <p class="mono text-xs text-gray-600 tracking-widest mb-1">TOTAL LOAN</p>
                                <p class="mono text-lg font-bold text-amber-400" id="preview-total">৳0.00</p>
                            </div>
                            <div class="text-gray-700 mono text-xs">÷</div>
                            <div class="text-center">
                                <p class="mono text-xs text-gray-600 tracking-widest mb-1">PER MONTH</p>
                                <p class="mono text-lg font-bold text-red-400" id="preview-monthly">৳0.00</p>
                            </div>
                            <div class="text-gray-700 mono text-xs">=</div>
                            <div class="text-center">
                                <p class="mono text-xs text-gray-600 tracking-widest mb-1">DURATION</p>
                                <p class="mono text-lg font-bold text-emerald-400" id="preview-months">— mo</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 my-8"></div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="submit-btn flex-1 bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold py-3.5 px-6 rounded-xl tracking-wide text-sm">
                            Save Loan
                        </button>
                        <a href="{{ route('employee.list') }}"
                           class="back-btn flex items-center gap-2 border border-gray-700 text-gray-400 font-semibold py-3.5 px-5 rounded-xl text-sm tracking-wide transition-all duration-200">
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
    const loanInput    = document.getElementById('loan_amount');
    const monthlyInput = document.getElementById('monthly_deduction');
    const previewTotal   = document.getElementById('preview-total');
    const previewMonthly = document.getElementById('preview-monthly');
    const previewMonths  = document.getElementById('preview-months');

    function updatePreview() {
        const loan    = parseFloat(loanInput.value) || 0;
        const monthly = parseFloat(monthlyInput.value) || 0;

        previewTotal.textContent   = '৳' + loan.toLocaleString('en-IN', { minimumFractionDigits: 2 });
        previewMonthly.textContent = '৳' + monthly.toLocaleString('en-IN', { minimumFractionDigits: 2 });

        if (monthly > 0 && loan > 0) {
            const months = Math.ceil(loan / monthly);
            previewMonths.textContent = months + ' mo';
        } else {
            previewMonths.textContent = '— mo';
        }
    }

    loanInput.addEventListener('input', updatePreview);
    monthlyInput.addEventListener('input', updatePreview);
</script>

@endsection