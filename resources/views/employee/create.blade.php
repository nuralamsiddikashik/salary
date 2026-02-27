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

    .form-shell { width: 100%; max-width: 480px; }

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

    .field input {
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
    .field input:focus { border-color: var(--accent); background: var(--surface); }
    .field input::placeholder { color: var(--text-muted); font-size: 0.82rem; }

    input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0.4; cursor: pointer; }
    input[type="date"]::-webkit-calendar-picker-indicator:hover { opacity: 0.8; }

    .input-wrap { position: relative; }
    .input-wrap .currency {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        font-family: 'DM Mono', monospace;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--gold);
        pointer-events: none;
    }
    .input-wrap input { padding-left: 1.85rem; }

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

        <div class="breadcrumb">
            <a href="{{ route('employee.list') }}">Employees</a>
            <span class="sep">></span>
            <span class="current">Add New</span>
        </div>

        <div class="form-card">

            <div class="card-head">
                <div>
                    <div class="eyebrow">Employee Management</div>
                    <h1>Add Employee</h1>
                </div>
                <a href="{{ route('employee.list') }}" class="btn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    View List
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('employee.store') }}" method="POST">
                    @csrf

                    <div class="field-group">

                        <div class="field">
                            <div class="field-row">
                                <label for="name">Full Name</label>
                                <span class="field-num">01</span>
                            </div>
                            <input type="text" id="name" name="name"
                                   placeholder="e.g. Rahul Ahmed"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="field">
                            <div class="field-row">
                                <label for="join_date">Join Date</label>
                                <span class="field-num">02</span>
                            </div>
                            <input type="date" id="join_date" name="join_date"
                                   value="{{ old('join_date') }}" required>
                        </div>

                        <div class="field">
                            <div class="field-row">
                                <label for="designation">Designation</label>
                                <span class="field-num">03</span>
                            </div>
                            <input type="text" id="designation" name="designation"
                                   placeholder="e.g. Senior Developer"
                                   value="{{ old('designation') }}" required>
                        </div>

                        <div class="field">
                            <div class="field-row">
                                <label for="total_salary">Total Salary</label>
                                <span class="field-num">04</span>
                            </div>
                            <div class="input-wrap">
                                <span class="currency">&#2547;</span>
                                <input type="number" id="total_salary" name="total_salary"
                                       placeholder="0.00" step="0.01" min="0"
                                       value="{{ old('total_salary') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="actions">
                        <button type="submit" class="btn-submit">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Employee
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

@endsection