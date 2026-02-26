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
    }

    .input-field:focus {
        border-bottom-color: #f59e0b;
    }

    .input-field::placeholder { color: #4b5563; }

    input[type="date"].input-field::-webkit-calendar-picker-indicator {
        filter: invert(0.3) sepia(1) saturate(3) hue-rotate(0deg);
        cursor: pointer;
        opacity: 0.5;
    }
    input[type="date"].input-field::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
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

    .list-btn:hover {
        border-color: #6b7280;
        color: #d1d5db;
        transform: translateY(-1px);
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
            <span class="mono text-xs text-gray-500 tracking-widest">EMPLOYEE MANAGEMENT</span>
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
                        Add Employee
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('employee.list') }}"
                       class="list-btn flex items-center gap-2 border border-gray-700 hover:border-amber-500 hover:border-opacity-50 text-gray-400 hover:text-amber-400 font-semibold py-2 px-4 rounded-xl text-xs tracking-widest transition-all duration-200"
                       style="font-family: 'Space Mono', monospace;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        VIEW LIST
                    </a>
                    <div class="w-12 h-12 rounded-xl bg-amber-500 bg-opacity-10 border border-amber-500 border-opacity-20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-8 py-8">
                <form action="{{ route('employee.store') }}" method="POST">
                    @csrf

                    <div class="space-y-8">

                        {{-- Name --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Name</label>
                                <span class="step-number">01</span>
                            </div>
                            <input type="text" name="name" class="input-field" placeholder="e.g. Rahul Ahmed" required>
                        </div>

                        {{-- Join Date --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Join Date</label>
                                <span class="step-number">02</span>
                            </div>
                            <input type="date" name="join_date" class="input-field" required>
                        </div>

                        {{-- Designation --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Designation</label>
                                <span class="step-number">03</span>
                            </div>
                            <input type="text" name="designation" class="input-field" placeholder="e.g. Senior Developer" required>
                        </div>

                        {{-- Total Salary --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="label-text">Total Salary</label>
                                <span class="step-number">04</span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-0 bottom-2 text-amber-500 font-bold mono text-sm">৳</span>
                                <input type="number" name="total_salary" class="input-field pl-5" placeholder="0.00" required>
                            </div>
                        </div>

                    </div>

                    <div class="border-t border-gray-800 my-8"></div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="submit-btn flex-1 bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold py-3.5 px-6 rounded-xl tracking-wide text-sm">
                            Save Employee
                        </button>
                        <a href="{{ route('employee.list') }}"
                           class="list-btn flex items-center gap-2 border border-gray-700 text-gray-400 font-semibold py-3.5 px-5 rounded-xl text-sm tracking-wide transition-all duration-200">
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

@endsection