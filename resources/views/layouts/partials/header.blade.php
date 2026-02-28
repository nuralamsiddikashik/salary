{{--
    Responsive Sidebar / Navbar
    ─────────────────────────────
    Desktop (≥1024px) : Fixed left sidebar, 220px wide
    Tablet  (768-1023): Collapsible sidebar, toggle button
    Mobile  (<768px)  : Top navbar + full-screen slide-down menu
--}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --sb-bg:         #ffffff;
        --sb-border:     #e2e2dc;
        --sb-hover:      #f5f5f0;
        --sb-active:     #e8eef4;
        --sb-active-txt: #1a3a5c;
        --sb-txt:        #5a5a54;
        --sb-muted:      #9a9a92;
        --sb-accent:     #1a3a5c;
        --sb-line:       #e2e2dc;
        --sb-red-hover:  #fdf0f0;
        --sb-red-txt:    #8a1a1a;
        --sb-width:      220px;
        --top-h:         56px;
    }

    /* ── Reset ── */
    .sb-root *, .sb-root *::before, .sb-root *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }

    /* ════════════════════════════════════════
       DESKTOP SIDEBAR (≥1024px)
    ════════════════════════════════════════ */
    .sidebar {
        width: var(--sb-width);
        background: var(--sb-bg);
        border-right: 1px solid var(--sb-border);
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 200;
        transition: transform 0.25s ease;
    }

    /* ── Brand ── */
    .sb-brand {
        padding: 1.25rem 1.4rem;
        border-bottom: 1px solid var(--sb-line);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-shrink: 0;
    }

    .sb-brand-icon {
        width: 1.9rem;
        height: 1.9rem;
        background: var(--sb-accent);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .sb-brand-icon svg { color: #fff; }

    .sb-brand-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: #1a1a18;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }

    .sb-brand-sub {
        font-family: 'DM Mono', monospace;
        font-size: 0.56rem;
        font-weight: 500;
        color: var(--sb-muted);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-top: 0.1rem;
    }

    /* ── Nav ── */
    .sb-nav {
        flex: 1;
        padding: 1rem 0.7rem;
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
        overflow-y: auto;
    }

    .sb-section-label {
        font-family: 'DM Mono', monospace;
        font-size: 0.56rem;
        font-weight: 600;
        color: var(--sb-muted);
        letter-spacing: 0.16em;
        text-transform: uppercase;
        padding: 0 0.55rem;
        margin: 0.8rem 0 0.35rem;
    }
    .sb-section-label:first-child { margin-top: 0; }

    .sb-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.52rem 0.6rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--sb-txt);
        text-decoration: none;
        transition: background 0.12s, color 0.12s;
        position: relative;
        white-space: nowrap;
    }
    .sb-link:hover { background: var(--sb-hover); color: #1a1a18; }
    .sb-link.active {
        background: var(--sb-active);
        color: var(--sb-active-txt);
        font-weight: 600;
    }
    .sb-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 20%;
        height: 60%; width: 3px;
        background: var(--sb-accent);
        border-radius: 0 2px 2px 0;
    }
    .sb-link svg {
        width: 1rem; height: 1rem;
        flex-shrink: 0;
        opacity: 0.55;
        transition: opacity 0.12s;
    }
    .sb-link:hover svg,
    .sb-link.active svg { opacity: 1; }

    /* ── Footer ── */
    .sb-footer {
        padding: 0.8rem 0.7rem;
        border-top: 1px solid var(--sb-line);
        flex-shrink: 0;
    }

    .sb-logout {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.52rem 0.6rem;
        border-radius: 6px;
        font-size: 0.76rem;
        font-weight: 500;
        color: var(--sb-muted);
        background: none;
        border: none;
        cursor: pointer;
        width: 100%;
        font-family: 'Inter', sans-serif;
        transition: background 0.12s, color 0.12s;
    }
    .sb-logout:hover { background: var(--sb-red-hover); color: var(--sb-red-txt); }
    .sb-logout svg { width: 1rem; height: 1rem; flex-shrink: 0; }

    /* ════════════════════════════════════════
       OVERLAY (tablet/mobile when menu open)
    ════════════════════════════════════════ */
    .sb-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        z-index: 190;
        opacity: 0;
        transition: opacity 0.25s;
    }
    .sb-overlay.visible {
        display: block;
        opacity: 1;
    }

    /* ════════════════════════════════════════
       TOP NAVBAR (tablet + mobile)
    ════════════════════════════════════════ */
    .sb-topbar {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0;
        height: var(--top-h);
        background: var(--sb-bg);
        border-bottom: 1px solid var(--sb-border);
        z-index: 200;
        padding: 0 1.1rem;
        align-items: center;
        justify-content: space-between;
    }

    .sb-topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        text-decoration: none;
    }

    .sb-topbar-icon {
        width: 1.75rem; height: 1.75rem;
        background: var(--sb-accent);
        border-radius: 5px;
        display: flex; align-items: center; justify-content: center;
    }
    .sb-topbar-icon svg { color: #fff; }

    .sb-topbar-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1a1a18;
        letter-spacing: -0.02em;
    }

    /* Hamburger button */
    .sb-hamburger {
        width: 2.2rem; height: 2.2rem;
        border: 1px solid var(--sb-border);
        border-radius: 6px;
        background: var(--sb-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.12s;
        flex-shrink: 0;
    }
    .sb-hamburger:hover { background: var(--sb-hover); }
    .sb-hamburger svg { width: 1.1rem; height: 1.1rem; color: var(--sb-txt); }

    /* ── Mobile drawer slides in from left ── */
    @media (max-width: 1023px) {
        .sb-topbar { display: flex; }

        .sidebar {
            transform: translateX(-100%);
            top: 0;
            /* Show on top of topbar when open */
            padding-top: 0;
        }
        .sidebar.open { transform: translateX(0); }
    }

    /* ── Large desktop: sidebar always visible, no topbar ── */
    @media (min-width: 1024px) {
        .sb-topbar   { display: none !important; }
        .sb-overlay  { display: none !important; }
        .sidebar     { transform: none !important; }
    }

    /* ── Tablet tweaks ── */
    @media (min-width: 768px) and (max-width: 1023px) {
        .sidebar { width: 240px; }
    }

    /* ── Mobile tweaks ── */
    @media (max-width: 767px) {
        .sidebar { width: 80vw; max-width: 280px; }
    }

    /* ── Body padding helper (applied via JS to .layout-body or main) ── */
    @media (min-width: 1024px) {
        body { padding-left: var(--sb-width); }
    }
    @media (max-width: 1023px) {
        body { padding-top: var(--top-h); }
    }
</style>

{{-- ════════════════════════════════════════
     TOP NAVBAR (tablet + mobile only)
════════════════════════════════════════ --}}
<div class="sb-topbar" id="sb-topbar">
    <div class="sb-topbar-brand">
        <div class="sb-topbar-icon">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="sb-topbar-name">Ashis Auto Solution</span>
    </div>
    <button class="sb-hamburger" id="sb-toggle" aria-label="Open menu">
        <svg id="sb-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="sb-icon-close" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- Overlay --}}
<div class="sb-overlay" id="sb-overlay"></div>

{{-- ════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ --}}
<aside class="sidebar" id="sb-sidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <div class="sb-brand-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="sb-brand-name">Ashis Auto Solution</div>
            <div class="sb-brand-sub">Payroll System</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sb-nav">

        <span class="sb-section-label">Main</span>

        <a href="#" class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <span class="sb-section-label">Management</span>

        <a href="{{ route('employee.create') }}" class="sb-link {{ request()->routeIs('employee.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Employees
        </a>

        <a href="{{ route('loan.create') }}" class="sb-link {{ request()->routeIs('loan.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Loans
        </a>

        <a href="{{ route('payroll.create') }}" class="sb-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Payroll
        </a>

        <span class="sb-section-label">Analytics</span>

        <a href="{{ route('report.index') }}" class="sb-link {{ request()->routeIs('report.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reports
        </a>



        <a href="{{ route('salary.payment.report') }}" class="sb-link {{ request()->routeIs('salary.payment.report') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Salary Payments
        </a>

    </nav>

    {{-- Footer --}}
    <div class="sb-footer">
        <form method="POST" action="">
            @csrf
            <button type="submit" class="sb-logout">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

</aside>

<script>
(function () {
    const sidebar  = document.getElementById('sb-sidebar');
    const overlay  = document.getElementById('sb-overlay');
    const toggle   = document.getElementById('sb-toggle');
    const iconOpen  = document.getElementById('sb-icon-open');
    const iconClose = document.getElementById('sb-icon-close');

    function openMenu() {
        sidebar.classList.add('open');
        overlay.classList.add('visible');
        iconOpen.style.display  = 'none';
        iconClose.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        sidebar.classList.remove('open');
        overlay.classList.remove('visible');
        iconOpen.style.display  = 'block';
        iconClose.style.display = 'none';
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeMenu() : openMenu();
    });

    overlay.addEventListener('click', closeMenu);

    // Close on nav link click (mobile UX)
    sidebar.querySelectorAll('.sb-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) closeMenu();
        });
    });

    // Close on resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) closeMenu();
    });
})();
</script>