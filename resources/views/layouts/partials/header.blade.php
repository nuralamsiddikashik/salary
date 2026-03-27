@php
    $user = auth()->user();
    function canAccess($permission) {
        $user = auth()->user();
        if (!$user) return false;
        if ($user->role === 'admin') return true;
        return $user->hasPermission($permission);
    }
@endphp

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

        --sb-w:      240px;
        --topbar-h:  52px;
    }

    .sb-topbar *, .sidebar * {
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    /* ── Body offset ── */
    body {
        margin: 0;
        padding-left: var(--sb-w);
    }

    /* ════════════════════════════
       TOP BAR — mobile only
    ════════════════════════════ */
    .sb-topbar {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0;
        height: var(--topbar-h);
        background: var(--surface);
        border-bottom: 2px solid var(--text-primary);
        z-index: 1000;
        align-items: center;
        justify-content: space-between;
        padding: 0 1rem;
    }

    .sb-topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .sb-topbar-icon {
        width: 28px; height: 28px;
        background: var(--accent);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sb-topbar-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .sb-hamburger {
        background: none;
        border: 1px solid var(--border-md);
        border-radius: 6px;
        color: var(--text-secondary);
        width: 34px; height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: border-color 0.15s, color 0.15s;
    }
    .sb-hamburger:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    /* ════════════════════════════
       OVERLAY
    ════════════════════════════ */
    .sb-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.3);
        z-index: 1010;
        backdrop-filter: blur(2px);
    }
    .sb-overlay.visible { display: block; }

    /* ════════════════════════════
       SIDEBAR
    ════════════════════════════ */
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: var(--sb-w);
        height: 100vh;
        background: var(--surface);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        z-index: 1020;
        overflow: hidden;
        transition: transform 0.28s cubic-bezier(0.16,1,0.3,1);
    }

    /* ── Brand ── */
    .sb-brand {
        padding: 1.1rem 1.1rem 0.9rem;
        border-bottom: 2px solid var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-shrink: 0;
    }

    .sb-brand-logo {
        width: 32px; height: 32px;
        background: var(--accent);
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .sb-brand-name {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .sb-brand-sub {
        font-size: 0.58rem;
        font-weight: 500;
        color: var(--text-muted);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-top: 2px;
        font-family: 'DM Mono', monospace;
    }

    /* ── User pill ── */
    .sb-user-pill {
        margin: 0.85rem 0.85rem 0;
        padding: 0.6rem 0.75rem;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 7px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-shrink: 0;
    }

    .sb-user-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: var(--accent-lt);
        color: var(--accent);
        font-size: 0.62rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1.5px solid var(--border-md);
    }

    .sb-user-info { min-width: 0; flex: 1; }

    .sb-user-name {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }

    .sb-user-role {
        font-size: 0.57rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-top: 2px;
        font-family: 'DM Mono', monospace;
    }
    .sb-user-role.admin  { color: var(--accent); }
    .sb-user-role.editor { color: var(--gold); }

    /* ── Navigation ── */
    .sb-nav {
        flex: 1;
        overflow-y: auto;
        padding: 0.75rem 0 0.5rem;
        scrollbar-width: none;
    }
    .sb-nav::-webkit-scrollbar { display: none; }

    /* Section label */
    .sb-section-label {
        display: block;
        font-size: 0.55rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: 0.7rem 1.1rem 0.28rem;
        font-family: 'DM Mono', monospace;
    }

    /* Nav link */
    .sb-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 0.85rem;
        margin: 0.08rem 0.5rem;
        border-radius: 6px;
        font-size: 0.74rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.15s ease;
        letter-spacing: -0.01em;
        position: relative;
    }

    .sb-link:hover {
        color: var(--text-primary);
        background: var(--bg);
    }

    .sb-link.active {
        color: var(--accent);
        background: var(--accent-lt);
        font-weight: 600;
    }

    /* Active left bar */
    .sb-link.active::before {
        content: '';
        position: absolute;
        left: -0.5rem;
        top: 22%; bottom: 22%;
        width: 3px;
        background: var(--accent);
        border-radius: 0 3px 3px 0;
    }

    /* Icon */
    .sb-link-icon {
        width: 17px; height: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--text-muted);
        transition: color 0.15s;
    }
    .sb-link:hover .sb-link-icon { color: var(--text-secondary); }
    .sb-link.active .sb-link-icon { color: var(--accent); }

    /* Nav divider */
    .sb-nav-divider {
        height: 1px;
        background: var(--border);
        margin: 0.55rem 1rem;
    }

    /* ── Footer ── */
    .sb-footer {
        padding: 0.85rem 0.85rem 0.9rem;
        border-top: 1px solid var(--border);
        flex-shrink: 0;
    }

    .sb-logout {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.52rem 1rem;
        background: var(--red-lt);
        border: 1px solid #e8c8c8;
        border-radius: 6px;
        color: var(--red);
        font-size: 0.72rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.15s;
        letter-spacing: 0.02em;
    }
    .sb-logout:hover {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }

    .sb-footer-copy {
        text-align: center;
        font-size: 0.57rem;
        color: var(--text-muted);
        margin-top: 0.6rem;
        font-family: 'DM Mono', monospace;
        letter-spacing: 0.06em;
    }

    /* ════════════════════════════
       RESPONSIVE
    ════════════════════════════ */
    @media (max-width: 768px) {
        body { padding-left: 0; padding-top: var(--topbar-h); }
        .sb-topbar { display: flex; }
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
    }
</style>

{{-- ══ TOP BAR (mobile) ══ --}}
<div class="sb-topbar" id="sb-topbar">
    <div class="sb-topbar-brand">
        <div class="sb-topbar-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>
        <span class="sb-topbar-name">Ashis Auto Solution</span>
    </div>
    <button class="sb-hamburger" id="sb-toggle" aria-label="Toggle menu">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>
</div>

<div class="sb-overlay" id="sb-overlay"></div>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar" id="sb-sidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <div class="sb-brand-logo">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
            </svg>
        </div>
        <div>
            <div class="sb-brand-name">Ashis Auto Solution</div>
            <div class="sb-brand-sub">Payroll System</div>
        </div>
    </div>

    {{-- User pill --}}
    <div class="sb-user-pill">
        <div class="sb-user-avatar">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div class="sb-user-info">
            <div class="sb-user-name">{{ $user->name }}</div>
            <div class="sb-user-role {{ $user->role }}">{{ ucfirst($user->role) }}</div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sb-nav">

        {{-- MAIN --}}
        <span class="sb-section-label">Main</span>
        <a href="{{ route('dashboard') }}"
           class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
            </span>
            Dashboard
        </a>

        {{-- MANAGEMENT --}}
        <span class="sb-section-label">Management</span>

        @if(canAccess('employee.view'))
        <a href="{{ route('employee.list') }}"
           class="sb-link {{ request()->routeIs('employee.*') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </span>
            Employees
        </a>
        @endif

        @if(canAccess('loan.create'))
        <a href="{{ route('loan.create') }}"
           class="sb-link {{ request()->routeIs('loan.*') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </span>
            Loans
        </a>
        @endif

        @if(canAccess('advance.create'))
        <a href="{{ route('advance.create') }}"
           class="sb-link {{ request()->routeIs('advance.*') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                </svg>
            </span>
            Advance
        </a>
        @endif

        @if(canAccess('payroll.create'))
        <a href="{{ route('payroll.create') }}"
           class="sb-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                </svg>
            </span>
            Payroll
        </a>
        @endif

        {{-- ANALYTICS --}}
        <span class="sb-section-label">Analytics</span>

        @if(canAccess('report.view'))
        <a href="{{ route('report.index') }}"
           class="sb-link {{ request()->routeIs('report.*') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </span>
            Reports
        </a>
        @endif

        @if(canAccess('report.view'))
        <a href="{{ route('salary.payment.report') }}"
           class="sb-link {{ request()->routeIs('salary.payment.report') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </span>
            Salary Payments
        </a>
        @endif

        {{-- ADMIN --}}
        @if($user->role === 'admin')
        <div class="sb-nav-divider"></div>
        <span class="sb-section-label">Admin</span>
        <a href="{{ route('users.index') }}"
           class="sb-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </span>
            User Management
        </a>
        <a href="{{ route('users.create') }}"
           class="sb-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
            <span class="sb-link-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
            </span>
            Add User
        </a>
        @endif

    </nav>

    {{-- Footer --}}
    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </button>
        </form>
        <div class="sb-footer-copy">&copy; 2026 Ashis Auto Solution</div>
    </div>

</aside>

{{-- ══ SCRIPT ══ --}}
<script>
(function () {
    const sidebar = document.getElementById('sb-sidebar');
    const overlay = document.getElementById('sb-overlay');
    const toggle  = document.getElementById('sb-toggle');
    if (!toggle) return;
    function openMenu()  { sidebar.classList.add('open');    overlay.classList.add('visible');    document.body.style.overflow = 'hidden'; }
    function closeMenu() { sidebar.classList.remove('open'); overlay.classList.remove('visible'); document.body.style.overflow = ''; }
    toggle.addEventListener('click', () => sidebar.classList.contains('open') ? closeMenu() : openMenu());
    overlay.addEventListener('click', closeMenu);
})();
</script>