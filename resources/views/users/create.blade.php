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

    /* ── Form Card ── */
    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        max-width: 680px;
    }

    .form-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: #f8f8f5;
    }

    .form-card-title {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-secondary);
    }

    .form-card-icon {
        width: 28px; height: 28px;
        background: var(--accent-lt);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
    }

    .form-card-body {
        padding: 1.75rem 1.5rem;
    }

    /* ── Form Grid ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.1rem 1.25rem;
    }

    .form-grid .span-2 { grid-column: 1 / -1; }

    /* ── Field ── */
    .field { display: flex; flex-direction: column; gap: 0.38rem; }

    .field-label {
        font-size: 0.6rem;
        font-weight: 700;
        color: var(--text-secondary);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .field-req {
        font-size: 0.58rem;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: none;
        letter-spacing: 0;
    }

    .field-input {
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
        -webkit-appearance: none;
    }

    .field-input::placeholder { color: var(--text-muted); font-size: 0.78rem; }

    .field-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(26,58,92,0.08);
        background: var(--surface);
    }

    /* Password wrapper */
    .pass-wrap { position: relative; }

    .pass-toggle {
        position: absolute;
        right: 10px; top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-muted);
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 4px;
        user-select: none;
        transition: color 0.15s;
    }
    .pass-toggle:hover { color: var(--accent); }

    /* Select */
    .field-select {
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%235a5a54'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.6rem center;
        background-size: 0.9rem;
        cursor: pointer;
        width: 100%;
    }
    .field-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(26,58,92,0.08);
    }
    .field-select option { background: #fff; color: var(--text-primary); }

    /* ── Section divider ── */
    .section-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 1.4rem 0 1.1rem;
    }

    .section-divider-label {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--text-muted);
        white-space: nowrap;
        font-family: 'DM Mono', monospace;
    }

    .section-divider-line {
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    /* ── Permissions grid ── */
    .perms-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .perm-item { cursor: pointer; user-select: none; }
    .perm-item input[type="checkbox"] { display: none; }

    .perm-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.75rem;
        border-radius: 5px;
        font-size: 0.67rem;
        font-weight: 500;
        border: 1px solid var(--border-md);
        background: var(--bg);
        color: var(--text-secondary);
        transition: all 0.15s;
        cursor: pointer;
    }

    .perm-chip-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.4;
        transition: opacity 0.15s;
        flex-shrink: 0;
    }

    .perm-item input:checked + .perm-chip {
        background: var(--accent-lt);
        border-color: #a8c0d6;
        color: var(--accent);
        font-weight: 600;
    }

    .perm-item input:checked + .perm-chip .perm-chip-dot { opacity: 1; }

    /* ── Form footer ── */
    .form-card-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.6rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
        background: #fafaf7;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.58rem 1.2rem;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: 5px;
        font-size: 0.74rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        letter-spacing: 0.04em;
        transition: background 0.15s;
    }
    .btn-submit:hover { background: #142d47; }

    /* ── Alert ── */
    .alert {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-size: 0.74rem;
        font-weight: 500;
        margin-bottom: 1.25rem;
    }
    .alert-error   { background: var(--red-lt);   border: 1px solid #e8c8c8; color: var(--red); }
    .alert-success { background: var(--green-lt);  border: 1px solid #b7e4c7; color: var(--green); }

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
    @media (max-width: 600px) {
        .pw { padding: 1.25rem 1rem; }
        .form-grid { grid-template-columns: 1fr; }
        .form-grid .span-2 { grid-column: 1; }
        .corp-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .form-card { max-width: 100%; }
    }
</style>

<div class="pw">
    <div class="relative w-full mx-auto">

        {{-- ── Page Header ── --}}
        <div class="corp-header">
            <div class="corp-header-left">
                <div class="eyebrow">User Management</div>
                <h1>Create User</h1>
                <div class="sub">Add a new user and assign roles &amp; permissions</div>
            </div>
            <div class="corp-header-right">
                <a href="{{ route('users.index') }}" class="btn btn-outline">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert alert-success">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><line x1="12" y1="8" x2="12" y2="12" stroke-width="2" stroke-linecap="round"/><line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2" stroke-linecap="round"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- ── Form Card ── --}}
        <div class="form-card">

            <div class="form-card-header">
                <span class="form-card-title">User Information</span>
                <div class="form-card-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="form-card-body">

                    {{-- Basic Info --}}
                    <div class="form-grid">

                        {{-- Name --}}
                        <div class="field">
                            <label class="field-label">
                                Full Name
                                <span class="field-req">Required</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="field-input"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Rahim Uddin"
                                   required autofocus>
                        </div>

                        {{-- Email --}}
                        <div class="field">
                            <label class="field-label">
                                Email Address
                                <span class="field-req">Required</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   class="field-input"
                                   value="{{ old('email') }}"
                                   placeholder="e.g. rahim@ashisauto.com"
                                   required>
                        </div>

                        {{-- Password --}}
                        <div class="field">
                            <label class="field-label">
                                Password
                                <span class="field-req">Required</span>
                            </label>
                            <div class="pass-wrap">
                                <input type="password"
                                       id="passwordField"
                                       name="password"
                                       class="field-input"
                                       placeholder="Min. 8 characters"
                                       style="padding-right: 3rem;"
                                       required>
                                <span class="pass-toggle" onclick="togglePass()">SHOW</span>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="field">
                            <label class="field-label">
                                Role
                                <span class="field-req">Required</span>
                            </label>
                            <select name="role" class="field-select">
                                <option value="admin"  {{ old('role') == 'admin'  ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                            </select>
                        </div>

                    </div>

                    {{-- Permissions --}}
                    <div class="section-divider">
                        <span class="section-divider-label">Permissions</span>
                        <div class="section-divider-line"></div>
                    </div>

                    <div class="perms-grid">
                        @foreach($permissions as $permission)
                        <label class="perm-item">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                            <span class="perm-chip">
                                <span class="perm-chip-dot"></span>
                                {{ $permission->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>

                </div>

                {{-- Footer --}}
                <div class="form-card-footer">
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create User
                    </button>
                </div>

            </form>
        </div>

        <div class="bottom-rule">End of Form</div>

    </div>
</div>

<script>
    function togglePass() {
        const f = document.getElementById('passwordField');
        const t = event.target;
        if (f.type === 'password') {
            f.type = 'text';
            t.textContent = 'HIDE';
        } else {
            f.type = 'password';
            t.textContent = 'SHOW';
        }
    }
</script>

@endsection