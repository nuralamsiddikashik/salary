<aside class="sidebar">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

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
        }

        .sidebar {
            width: 220px;
            background: var(--sb-bg);
            border-right: 1px solid var(--sb-border);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            flex-shrink: 0;
        }

        /* Brand */
        .sb-brand {
            padding: 1.35rem 1.5rem;
            border-bottom: 1px solid var(--sb-line);
            display: flex;
            align-items: center;
            gap: 0.6rem;
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
            font-size: 0.9rem;
            font-weight: 700;
            color: #1a1a18;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .sb-brand-sub {
            font-size: 0.58rem;
            font-weight: 500;
            color: var(--sb-muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-family: 'DM Mono', monospace;
            margin-top: 0.15rem;
        }

        /* Nav */
        .sb-nav {
            flex: 1;
            padding: 1.1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .sb-section-label {
            font-size: 0.58rem;
            font-weight: 600;
            color: var(--sb-muted);
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-family: 'DM Mono', monospace;
            padding: 0 0.6rem;
            margin: 0.75rem 0 0.4rem;
        }

        .sb-section-label:first-child { margin-top: 0; }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 0.65rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--sb-txt);
            text-decoration: none;
            transition: all 0.12s ease;
            position: relative;
        }

        .sb-link:hover {
            background: var(--sb-hover);
            color: #1a1a18;
        }

        .sb-link.active {
            background: var(--sb-active);
            color: var(--sb-active-txt);
            font-weight: 600;
        }

        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 3px;
            background: var(--sb-accent);
            border-radius: 0 2px 2px 0;
        }

        .sb-link svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
            opacity: 0.6;
        }

        .sb-link.active svg,
        .sb-link:hover svg { opacity: 1; }

        /* Footer */
        .sb-footer {
            padding: 0.85rem 0.75rem;
            border-top: 1px solid var(--sb-line);
        }

        .sb-logout {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 0.65rem;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--sb-muted);
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.12s ease;
        }

        .sb-logout:hover {
            background: #fdf0f0;
            color: #8a1a1a;
        }

        .sb-logout svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
        }
    </style>

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