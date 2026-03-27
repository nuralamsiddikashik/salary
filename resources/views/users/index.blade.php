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

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 1.1rem;
        background: var(--accent);
        color: #fff;
        border: 1px solid var(--accent);
        border-radius: 5px;
        font-size: 0.72rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        letter-spacing: 0.04em;
        transition: background 0.15s;
    }
    .btn-save:hover { background: #142d47; }

    /* ── User Grid ── */
    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
        gap: 1.25rem;
    }

    /* ── User Card ── */
    .user-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.15s;
    }
    .user-card:hover { box-shadow: 0 4px 16px rgba(26,58,92,0.08); }

    /* Card header */
    .user-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.25rem;
        background: #f8f8f5;
        border-bottom: 1px solid var(--border);
        gap: 0.75rem;
    }

    .user-meta {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        min-width: 0;
    }

    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--accent-lt);
        color: var(--accent);
        font-size: 0.72rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1.5px solid #c8d8e8;
        letter-spacing: 0.02em;
    }

    .user-name {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-email {
        font-size: 0.68rem;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
        margin-top: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-head-right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    /* Active toggle */
    .active-toggle {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        user-select: none;
    }

    .toggle-switch {
        position: relative;
        width: 30px;
        height: 17px;
        flex-shrink: 0;
        cursor: pointer;
    }

    .toggle-switch input { opacity: 0; width: 0; height: 0; }

    .toggle-track {
        position: absolute;
        inset: 0;
        background: var(--border-md);
        border-radius: 17px;
        transition: background 0.2s;
        cursor: pointer;
    }

    .toggle-track::before {
        content: '';
        position: absolute;
        width: 11px;
        height: 11px;
        background: #fff;
        border-radius: 50%;
        top: 3px;
        left: 3px;
        transition: transform 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }

    .toggle-switch input:checked + .toggle-track { background: var(--green); }
    .toggle-switch input:checked + .toggle-track::before { transform: translateX(13px); }

    /* Card body */
    .user-card-body { padding: 1.1rem 1.25rem; }

    /* Row */
    .field-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .field-row:last-child { margin-bottom: 0; }

    .field-label-sm {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        width: 72px;
        flex-shrink: 0;
    }

    .role-select {
        flex: 1;
        background: var(--bg);
        border: 1px solid var(--border-md);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 0.42rem 2rem 0.42rem 0.65rem;
        font-size: 0.78rem;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%235a5a54'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 0.85rem;
        cursor: pointer;
    }
    .role-select:focus { border-color: var(--accent); box-shadow: 0 0 0 2px rgba(26,58,92,0.08); }

    /* Role badge next to select */
    .role-badge {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .role-badge.admin  { background: var(--accent-lt); color: var(--accent); }
    .role-badge.editor { background: var(--gold-lt);   color: var(--gold); }

    /* Divider */
    .card-divider {
        height: 1px;
        background: var(--border);
        margin: 1rem 0;
    }

    /* Permissions section */
    .perm-label {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .perm-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .perms-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .perm-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        cursor: pointer;
        user-select: none;
    }

    .perm-item input[type="checkbox"] { display: none; }

    .perm-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.28rem 0.7rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 500;
        border: 1px solid var(--border-md);
        background: var(--bg);
        color: var(--text-secondary);
        transition: all 0.15s;
        cursor: pointer;
    }

    .perm-item input:checked + .perm-chip {
        background: var(--accent-lt);
        border-color: #a8c0d6;
        color: var(--accent);
        font-weight: 600;
    }

    .perm-chip-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.5;
        transition: opacity 0.15s;
    }

    .perm-item input:checked + .perm-chip .perm-chip-dot { opacity: 1; }

    /* Card footer */
    .user-card-foot {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0.8rem 1.25rem;
        border-top: 1px solid var(--border);
        background: #fafaf7;
        gap: 0.5rem;
    }

    .save-status {
        font-size: 0.65rem;
        color: var(--text-muted);
        font-family: 'DM Mono', monospace;
        flex: 1;
    }

    /* ── Empty State ── */
    .empty-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-muted);
        grid-column: 1 / -1;
    }
    .empty-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; opacity: 0.3; }
    .empty-text { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; }

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
        .users-grid { grid-template-columns: 1fr; }
        .corp-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>

<div class="pw">
    <div class="relative w-full mx-auto">

        {{-- ── Page Header ── --}}
        <div class="corp-header">
            <div class="corp-header-left">
                <div class="eyebrow">Payroll Management System</div>
                <h1>User Roles &amp; Permissions</h1>
                <div class="sub">Manage access control &nbsp;·&nbsp; {{ count($users) }} user(s)</div>
            </div>
            <div class="corp-header-right">
                <a href="{{ route('report.index') }}" class="btn btn-outline">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        {{-- ── Session alerts ── --}}
        @if(session('success'))
        <div style="display:flex;align-items:center;gap:0.6rem;background:var(--green-lt);border:1px solid #b7e4c7;color:var(--green);padding:0.75rem 1.1rem;border-radius:7px;font-size:0.75rem;font-weight:600;margin-bottom:1.25rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div style="display:flex;align-items:center;gap:0.6rem;background:var(--red-lt);border:1px solid #f5c6c6;color:var(--red);padding:0.75rem 1.1rem;border-radius:7px;font-size:0.75rem;font-weight:600;margin-bottom:1.25rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" y1="8" x2="12" y2="12" stroke-linecap="round" stroke-width="2"/><line x1="12" y1="16" x2="12.01" y2="16" stroke-linecap="round" stroke-width="2"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- ── Users Grid ── --}}
        <div class="users-grid">

            @forelse($users as $user)
            <form method="POST" action="{{ route('user.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="user-card">

                    {{-- Card Head --}}
                    <div class="user-card-head">
                        <div class="user-meta">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div style="min-width:0;">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="user-head-right">
                            <label class="active-toggle" title="Active / Inactive">
                                <input type="hidden" name="is_active" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                                    <span class="toggle-track"></span>
                                </label>
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </label>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="user-card-body">

                        {{-- Role --}}
                        <div class="field-row">
                            <span class="field-label-sm">Role</span>
                            <select name="role" class="role-select" onchange="updateBadge(this)">
                                <option value="admin"  {{ $user->role == 'admin'  ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ $user->role == 'editor' ? 'selected' : '' }}>Editor</option>
                            </select>
                            <span class="role-badge {{ $user->role }}" id="badge-{{ $user->id }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        {{-- Password --}}
                        <div class="field-row">
                            <span class="field-label-sm">Password</span>
                            <input type="password"
                                   name="password"
                                   class="role-select"
                                   style="flex:1;"
                                   placeholder="Leave blank to keep current password">
                        </div>

                        {{-- Confirm Password --}}
                        <div class="field-row">
                            <span class="field-label-sm">Confirm</span>
                            <input type="password"
                                   name="password_confirmation"
                                   class="role-select"
                                   style="flex:1;"
                                   placeholder="Leave blank to keep current password">
                        </div>

                        {{-- Permissions --}}
                        <div class="card-divider"></div>

                        <div class="perm-label">Permissions</div>

                        <div class="perms-grid">
                            @foreach($permissions as $permission)
                            <label class="perm-item">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       {{ $user->permissions->contains($permission->id) ? 'checked' : '' }}>
                                <span class="perm-chip">
                                    <span class="perm-chip-dot"></span>
                                    {{ $permission->name }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                    </div>

                    {{-- Card Footer --}}
                    <div class="user-card-foot">
                        <span class="save-status">ID&nbsp;·&nbsp;{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <button type="submit" class="btn-save">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>

                </div>
            </form>

            @empty
            <div class="empty-card">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div class="empty-text">No users found</div>
            </div>
            @endforelse

        </div>

        <div class="bottom-rule">End of User List</div>

    </div>
</div>

<script>
    function updateBadge(select) {
        const form = select.closest('form');
        const badge = form.querySelector('[id^="badge-"]');
        if (!badge) return;
        const val = select.value;
        badge.textContent = val.charAt(0).toUpperCase() + val.slice(1);
        badge.className = 'role-badge ' + val;
    }

    document.querySelectorAll('.toggle-switch').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            const label = this.closest('.active-toggle');
            const text = label.childNodes[label.childNodes.length - 1];
            text.textContent = ' ' + (checkbox.checked ? 'Active' : 'Inactive');
        });
    });
</script>

@endsection