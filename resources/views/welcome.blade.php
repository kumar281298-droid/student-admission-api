<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPortal AI - Student Admission & College Management System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for fast standalone UI rendering) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-modal {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.3);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.6);
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex flex-col font-sans antialiased custom-scrollbar overflow-x-hidden">

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-md w-full pointer-events-none"></div>

    <!-- Quick Role Switcher Banner (Evaluator Bar) -->
    <header class="bg-slate-900/90 border-b border-slate-800 sticky top-0 z-40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand & Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 via-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-lg text-white tracking-tight">EduPortal <span class="text-brand-400">AI</span></span>
                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Backend API Online
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 hidden sm:block">Student Admission & College Management System</p>
                    </div>
                </div>

                <!-- Quick Role Switcher -->
                <div class="hidden lg:flex items-center gap-2 bg-slate-950/80 p-1.5 rounded-xl border border-slate-800">
                    <span class="text-xs font-semibold text-slate-400 px-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"/></svg>
                        Test Roles:
                    </span>
                    <button onclick="quickLogin('admin@system.com', 'password')" class="px-2.5 py-1 text-xs font-medium bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 border border-purple-500/30 rounded-lg transition-all flex items-center gap-1">
                        👑 System Admin
                    </button>
                    <button onclick="quickLogin('collegeadmin@delhiuniv.ac.in', 'password')" class="px-2.5 py-1 text-xs font-medium bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-lg transition-all flex items-center gap-1">
                        🏛️ College Admin
                    </button>
                    <button onclick="quickLogin('student1@gmail.com', 'password')" class="px-2.5 py-1 text-xs font-medium bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-lg transition-all flex items-center gap-1">
                        🎓 Student 1 (Rahul)
                    </button>
                    <button onclick="quickLogin('student2@gmail.com', 'password')" class="px-2.5 py-1 text-xs font-medium bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 rounded-lg transition-all flex items-center gap-1">
                        🎓 Student 2 (Priya)
                    </button>
                </div>

                <!-- User Profile / Auth Action -->
                <div class="flex items-center gap-3" id="auth-header-container">
                    <!-- Dynamic Auth Button or User Badge -->
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <nav class="bg-slate-900 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between overflow-x-auto custom-scrollbar">
            <div class="flex space-x-1 py-2" id="nav-tabs">
                <button onclick="switchTab('dashboard')" id="tab-dashboard" class="nav-btn px-4 py-2 text-sm font-medium rounded-lg text-white bg-brand-600/20 text-brand-300 border border-brand-500/30 flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </button>
                <button onclick="switchTab('colleges')" id="tab-colleges" class="nav-btn px-4 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Colleges & Courses
                </button>
                <button onclick="switchTab('applications')" id="tab-applications" class="nav-btn px-4 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Applications
                </button>
                <button onclick="switchTab('profile')" id="tab-profile" class="nav-btn px-4 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 flex items-center gap-2 transition-all hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    My Profile
                </button>
                <button onclick="switchTab('audit')" id="tab-audit" class="nav-btn px-4 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 flex items-center gap-2 transition-all hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Audit Logs
                </button>
            </div>
            <div class="lg:hidden py-2">
                <select onchange="quickLogin(this.value, 'password')" class="bg-slate-950 border border-slate-700 text-xs text-slate-200 rounded-lg p-1.5">
                    <option value="">Switch Role Login...</option>
                    <option value="admin@system.com">System Admin</option>
                    <option value="collegeadmin@delhiuniv.ac.in">DU College Admin</option>
                    <option value="student1@gmail.com">Student 1 (Rahul)</option>
                    <option value="student2@gmail.com">Student 2 (Priya)</option>
                </select>
            </div>
        </div>
    </nav>

    <!-- App Content Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Role Context Banner -->
        <div id="role-context-banner" class="glass-card p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-l-4 border-l-brand-500">
            <div class="flex items-center gap-3">
                <div id="role-avatar-badge" class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-lg">
                    👤
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white flex items-center gap-2" id="role-title-text">
                        Guest Mode (Not Logged In)
                    </h2>
                    <p class="text-xs text-slate-400" id="role-desc-text">
                        Use the test role buttons at the top to quickly test Admin, College Admin, or Student permissions.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2" id="role-action-buttons">
                <!-- Action Buttons based on role -->
            </div>
        </div>

        <!-- TAB 1: DASHBOARD & STATS -->
        <div id="section-dashboard" class="tab-section space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="stats-cards-grid">
                <div class="glass-card p-5 rounded-2xl space-y-2 relative overflow-hidden group">
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-xs font-semibold tracking-wider uppercase">Colleges</span>
                        <div class="p-2 bg-indigo-500/10 text-indigo-400 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    </div>
                    <div class="text-3xl font-extrabold text-white" id="stat-colleges-total">-</div>
                    <p class="text-xs text-emerald-400 flex items-center gap-1" id="stat-colleges-active">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active Institutes
                    </p>
                </div>

                <div class="glass-card p-5 rounded-2xl space-y-2 relative overflow-hidden group">
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-xs font-semibold tracking-wider uppercase">Courses & Seats</span>
                        <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                    </div>
                    <div class="text-3xl font-extrabold text-white" id="stat-seats-avail">-</div>
                    <p class="text-xs text-slate-400" id="stat-seats-detail">Available / Total Seats</p>
                </div>

                <div class="glass-card p-5 rounded-2xl space-y-2 relative overflow-hidden group">
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-xs font-semibold tracking-wider uppercase">Total Applications</span>
                        <div class="p-2 bg-amber-500/10 text-amber-400 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    </div>
                    <div class="text-3xl font-extrabold text-white" id="stat-apps-total">-</div>
                    <p class="text-xs text-amber-300" id="stat-apps-pending">Submitted & Pending Review</p>
                </div>

                <div class="glass-card p-5 rounded-2xl space-y-2 relative overflow-hidden group">
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-xs font-semibold tracking-wider uppercase">Admissions Granted</span>
                        <div class="p-2 bg-purple-500/10 text-purple-400 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    </div>
                    <div class="text-3xl font-extrabold text-emerald-400" id="stat-apps-approved">-</div>
                    <p class="text-xs text-slate-400" id="stat-apps-rejected">Approved vs Rejected</p>
                </div>
            </div>

            <!-- Workflow Highlights & Interactive Guide -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- System Workflow Map -->
                <div class="lg:col-span-2 glass-card p-6 rounded-2xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Admission Process Flow & System Guardrails
                        </h3>
                        <span class="text-xs text-slate-400 bg-slate-950 px-2.5 py-1 rounded-lg border border-slate-800">RBAC Enforced</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800/80 space-y-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 font-bold flex items-center justify-center text-sm">1</div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Student Registration</h4>
                            <p class="text-xs text-slate-400 leading-relaxed">Students create profile, upload photo, view active colleges & available course seat counters.</p>
                        </div>
                        <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800/80 space-y-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 font-bold flex items-center justify-center text-sm">2</div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Application & Validation</h4>
                            <p class="text-xs text-slate-400 leading-relaxed">System validates seat count, college association, and prevents duplicate active applications.</p>
                        </div>
                        <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800/80 space-y-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 font-bold flex items-center justify-center text-sm">3</div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">AI Analysis & Approval</h4>
                            <p class="text-xs text-slate-400 leading-relaxed">Admins generate AI candidate summaries, approve/reject with automatic atomic seat deduction.</p>
                        </div>
                    </div>

                    <!-- Quick Testing Launcher -->
                    <div class="bg-gradient-to-r from-brand-900/40 via-indigo-900/20 to-purple-900/40 p-4 rounded-xl border border-brand-500/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-semibold text-white">Ready to test the complete flow?</h4>
                            <p class="text-xs text-slate-300">Click below to submit a test application as Student or review applications as Admin.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="openApplyModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-1.5 whitespace-nowrap">
                                📝 Submit New Application
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Audit Log Feed -->
                <div class="glass-card p-6 rounded-2xl space-y-4 flex flex-col">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Live Activity Stream
                        </h3>
                        <button onclick="fetchStats()" class="text-xs text-brand-400 hover:text-brand-300">Refresh</button>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-72 space-y-3 custom-scrollbar pr-1" id="recent-activity-list">
                        <div class="text-xs text-slate-500 text-center py-6">Loading activity feed...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: COLLEGES & COURSES -->
        <div id="section-colleges" class="tab-section hidden space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">Colleges & Course Directory</h2>
                    <p class="text-xs text-slate-400">Explore participating institutions and real-time available seat counts.</p>
                </div>
                <div class="flex items-center gap-2" id="college-admin-actions">
                    <!-- Admin can add college -->
                </div>
            </div>

            <!-- Colleges List Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="colleges-grid">
                <!-- Dynamic Colleges Cards -->
            </div>
        </div>

        <!-- TAB 3: APPLICATIONS MANAGEMENT -->
        <div id="section-applications" class="tab-section hidden space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">Student Applications Directory</h2>
                    <p class="text-xs text-slate-400">Search, filter, review AI summaries, approve, or reject applications.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button onclick="openApplyModal()" id="btn-submit-app-header" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New Application
                    </button>
                </div>
            </div>

            <!-- Search & Filters Toolbar -->
            <div class="glass-card p-4 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" id="app-search-input" onkeyup="debounceSearchApplications()" placeholder="Search App # or Student..." class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-9 pr-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand-500">
                    <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Status Filter -->
                <select id="app-status-filter" onchange="fetchApplications(1)" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-brand-500">
                    <option value="">All Statuses</option>
                    <option value="SUBMITTED">SUBMITTED</option>
                    <option value="UNDER_REVIEW">UNDER_REVIEW</option>
                    <option value="APPROVED">APPROVED</option>
                    <option value="REJECTED">REJECTED</option>
                </select>

                <!-- College Filter -->
                <select id="app-college-filter" onchange="fetchApplications(1)" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-brand-500">
                    <option value="">All Colleges</option>
                </select>

                <!-- Reset Button -->
                <button onclick="resetAppFilters()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-xs rounded-xl transition-all flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset Filters
                </button>
            </div>

            <!-- Applications Table Card -->
            <div class="glass-card rounded-2xl overflow-hidden border border-slate-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800 uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-4">Application #</th>
                                <th class="py-3.5 px-4">Student</th>
                                <th class="py-3.5 px-4">College & Course</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Submitted At</th>
                                <th class="py-3.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="applications-tbody" class="divide-y divide-slate-800/60">
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">Loading applications...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 bg-slate-900/60 border-t border-slate-800 flex items-center justify-between" id="app-pagination-container">
                    <!-- Pagination Controls -->
                </div>
            </div>
        </div>

        <!-- TAB 4: MY PROFILE (Student Only) -->
        <div id="section-profile" class="tab-section hidden space-y-6 max-w-3xl mx-auto">
            <div class="glass-card p-6 rounded-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-white tracking-tight">Student Profile Management</h2>
                        <p class="text-xs text-slate-400">Keep your personal information and photo updated for college verification.</p>
                    </div>
                    <span id="profile-reg-no-badge" class="px-3 py-1 bg-brand-500/10 text-brand-300 border border-brand-500/20 rounded-full font-mono text-xs font-semibold">
                        Reg: -
                    </span>
                </div>

                <form id="profile-form" onsubmit="saveProfile(event)" class="space-y-4">
                    <!-- Photo Upload Banner -->
                    <div class="flex items-center gap-6 p-4 bg-slate-900/90 rounded-xl border border-slate-800">
                        <div class="relative w-20 h-20 rounded-2xl overflow-hidden bg-slate-800 border-2 border-brand-500/40 flex items-center justify-center shrink-0">
                            <img id="profile-preview-img" src="" class="w-full h-full object-cover hidden">
                            <span id="profile-avatar-placeholder" class="text-2xl">🎓</span>
                        </div>
                        <div class="space-y-1.5 flex-1">
                            <label class="block text-xs font-medium text-slate-300">Profile Photo (Max 2MB, JPG/PNG)</label>
                            <input type="file" id="profile-photo-input" accept="image/jpeg,image/png" onchange="previewProfilePhoto(event)" class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-500 cursor-pointer">
                            <p class="text-[10px] text-slate-500">Validation: Strict MIME check, safe filename hash, stored via Laravel Storage disk.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Full Name</label>
                            <input type="text" id="prof-name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Email Address</label>
                            <input type="email" id="prof-email" disabled class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Mobile Number</label>
                            <input type="text" id="prof-mobile" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Date of Birth</label>
                            <input type="date" id="prof-dob" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Gender</label>
                            <select id="prof-gender" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-400 mb-1">Address</label>
                            <textarea id="prof-address" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500"></textarea>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 5: AUDIT LOGS (Admin Only) -->
        <div id="section-audit" class="tab-section hidden space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">System Audit & Compliance Log</h2>
                    <p class="text-xs text-slate-400">Complete audit trail of user logins, application status changes, and seat adjustments.</p>
                </div>
                <button onclick="fetchAuditLogs(1)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs text-slate-200 rounded-lg">Refresh Trail</button>
            </div>

            <div class="glass-card rounded-2xl overflow-hidden border border-slate-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800 uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-4">Timestamp</th>
                                <th class="py-3.5 px-4">User</th>
                                <th class="py-3.5 px-4">Action</th>
                                <th class="py-3.5 px-4">Entity</th>
                                <th class="py-3.5 px-4">Entity ID</th>
                                <th class="py-3.5 px-4">IP Address</th>
                            </tr>
                        </thead>
                        <tbody id="audit-tbody" class="divide-y divide-slate-800/60">
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">Loading audit logs...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-slate-900/60 border-t border-slate-800" id="audit-pagination"></div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="mt-auto bg-slate-950 border-t border-slate-900 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500 space-y-1">
            <p>Integrated Software Systems — Senior Laravel Developer Assignment Implementation</p>
            <p class="text-[11px] text-slate-600">Built with Laravel 11, Sanctum API Auth, SQLite, and Modern Glassmorphism UI.</p>
        </div>
    </footer>

    <!-- MODAL 1: AUTHENTICATION / MANUAL LOGIN -->
    <div id="modal-auth" onclick="if(event.target === this) closeModal('modal-auth')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
        <div class="glass-modal w-full max-w-md p-6 rounded-3xl space-y-5 shadow-2xl relative border border-slate-800 text-left max-h-[90vh] overflow-y-auto custom-scrollbar my-auto">
            <button onclick="closeModal('modal-auth')" class="absolute top-4 right-4 text-slate-400 hover:text-white w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm transition-colors">✕</button>
            <div class="text-center space-y-1">
                <h3 class="text-lg font-bold text-white">System Access</h3>
                <p class="text-xs text-slate-400">Sign in to your account or register as a new student</p>
            </div>

            <!-- Auth Mode Switcher -->
            <div class="flex p-1 bg-slate-950 rounded-xl border border-slate-800">
                <button onclick="toggleAuthMode('login')" id="btn-mode-login" class="flex-1 py-2 text-xs font-semibold rounded-lg bg-brand-600 text-white transition-all">Sign In</button>
                <button onclick="toggleAuthMode('register')" id="btn-mode-register" class="flex-1 py-2 text-xs font-semibold rounded-lg text-slate-400 hover:text-white transition-all">Register Student</button>
            </div>

            <!-- Login Form -->
            <form id="form-login" onsubmit="handleLoginSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Email Address</label>
                    <input type="email" id="login-email" required placeholder="admin@system.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Password</label>
                    <input type="password" id="login-password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
                <button type="submit" class="w-full py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all">
                    Sign In to Portal
                </button>
            </form>

            <!-- Register Form -->
            <form id="form-register" onsubmit="handleRegisterSubmit(event)" class="space-y-3 hidden">
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Full Name</label>
                    <input type="text" id="reg-name" required placeholder="Amit Sharma" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Email Address</label>
                    <input type="email" id="reg-email" required placeholder="student@example.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">Mobile</label>
                        <input type="text" id="reg-mobile" placeholder="9876543210" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">Password</label>
                        <input type="password" id="reg-password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                    Complete Student Registration
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 2: SUBMIT APPLICATION -->
    <div id="modal-apply" onclick="if(event.target === this) closeModal('modal-apply')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
        <div class="glass-modal w-full max-w-lg p-6 rounded-3xl space-y-5 shadow-2xl relative border border-slate-800 text-left max-h-[90vh] overflow-y-auto custom-scrollbar my-auto">
            <button onclick="closeModal('modal-apply')" class="absolute top-4 right-4 text-slate-400 hover:text-white w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm transition-colors">✕</button>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    📝 Submit Admission Application
                </h3>
                <p class="text-xs text-slate-400">Select target college & course. Seats will be validated automatically.</p>
            </div>

            <form id="form-apply" onsubmit="handleApplicationSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Select College</label>
                    <select id="apply-college-id" required onchange="onApplyCollegeChange(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                        <option value="">-- Choose College --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Select Course</label>
                    <select id="apply-course-id" required onchange="onApplyCourseChange(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                        <option value="">-- Select College First --</option>
                    </select>
                </div>

                <!-- Seat Availability Live Check Box -->
                <div id="apply-seat-checker" class="p-3 bg-slate-900/90 rounded-xl border border-slate-800 text-xs hidden">
                    <!-- Live seat availability info -->
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Applicant Statement / Remarks</label>
                    <textarea id="apply-remarks" rows="2" placeholder="Describe your academic interest or motivation..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-apply')" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs rounded-xl">Cancel</button>
                    <button type="submit" id="btn-submit-app-action" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-medium rounded-xl shadow-lg shadow-brand-500/20">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: APPLICATION DETAILS & REVIEWS -->
    <div id="modal-app-details" onclick="if(event.target === this) closeModal('modal-app-details')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
        <div class="glass-modal w-full max-w-2xl rounded-3xl space-y-0 border border-slate-800 relative text-left max-h-[90vh] flex flex-col shadow-2xl overflow-hidden my-auto">
            <!-- Modal Header -->
            <div class="p-5 border-b border-slate-800 flex items-center justify-between shrink-0 bg-slate-900/90">
                <div>
                    <span id="detail-app-no" class="text-xs font-mono font-bold text-brand-400 uppercase tracking-widest">APP-000</span>
                    <h3 class="text-lg font-bold text-white" id="detail-student-name">Student Details</h3>
                </div>
                <div class="flex items-center gap-3">
                    <span id="detail-status-badge"></span>
                    <button onclick="closeModal('modal-app-details')" class="w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center text-sm transition-colors">✕</button>
                </div>
            </div>

            <!-- Modal Content Body -->
            <div class="p-6 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800/80 space-y-2">
                        <h4 class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Student Profile</h4>
                        <p class="text-white"><strong class="text-slate-400">Name:</strong> <span id="detail-student-fullname">-</span></p>
                        <p class="text-white"><strong class="text-slate-400">Email:</strong> <span id="detail-student-email">-</span></p>
                        <p class="text-white"><strong class="text-slate-400">Mobile:</strong> <span id="detail-student-mobile">-</span></p>
                        <p class="text-white"><strong class="text-slate-400">Gender:</strong> <span id="detail-student-gender">-</span></p>
                    </div>

                    <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800/80 space-y-2">
                        <h4 class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Applied Course & Institute</h4>
                        <p class="text-white"><strong class="text-slate-400">College:</strong> <span id="detail-college-name">-</span></p>
                        <p class="text-white"><strong class="text-slate-400">Course:</strong> <span id="detail-course-name">-</span></p>
                        <p class="text-white"><strong class="text-slate-400">Duration:</strong> <span id="detail-course-duration">-</span></p>
                        <p class="text-white"><strong class="text-slate-400">Seats Status:</strong> <span id="detail-course-seats">-</span></p>
                    </div>
                </div>

                <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800 space-y-1 text-xs">
                    <h4 class="font-bold text-slate-400 uppercase text-[10px] tracking-wider">Student Remarks / Motivation</h4>
                    <p id="detail-remarks" class="text-slate-200 italic">-</p>
                </div>

                <!-- Rejection Reason if any -->
                <div id="detail-rejection-box" class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-xs text-rose-300 hidden space-y-1">
                    <h4 class="font-bold uppercase text-[10px] tracking-wider text-rose-400">Rejection Reason</h4>
                    <p id="detail-rejection-text">-</p>
                </div>

                <!-- AI Summary Section Button & Drawer Trigger -->
                <div class="p-4 bg-gradient-to-r from-purple-900/30 to-indigo-900/30 border border-purple-500/30 rounded-xl space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 bg-purple-500/20 text-purple-300 rounded-lg text-sm">🤖</span>
                            <div>
                                <h4 class="text-xs font-bold text-white">AI Candidate Summary Service</h4>
                                <p class="text-[11px] text-slate-400">Uses LLM engine to synthesize student eligibility and generate decision assistance.</p>
                            </div>
                        </div>
                        <button onclick="generateAISummary()" id="btn-trigger-ai" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-500 text-white font-semibold text-xs rounded-lg shadow-lg shadow-purple-500/20 flex items-center gap-1 transition-all">
                            Generate AI Summary
                        </button>
                    </div>
                    <div id="ai-summary-output-container" class="hidden text-xs space-y-2 pt-2 border-t border-purple-500/20">
                        <!-- AI response will inject here -->
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/90 flex items-center justify-between shrink-0" id="detail-admin-actions">
                <button onclick="closeModal('modal-app-details')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-xl transition-colors">
                    Close Window
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 4: CREATE COLLEGE (Admin Only) -->
    <div id="modal-create-college" onclick="if(event.target === this) closeModal('modal-create-college')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
        <div class="glass-modal w-full max-w-lg p-6 rounded-3xl space-y-5 shadow-2xl relative border border-slate-800 text-left max-h-[90vh] overflow-y-auto custom-scrollbar my-auto">
            <button onclick="closeModal('modal-create-college')" class="absolute top-4 right-4 text-slate-400 hover:text-white w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm transition-colors">✕</button>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-white">🏛️ Register New College</h3>
                <p class="text-xs text-slate-400">Add an educational institution to the system.</p>
            </div>

            <form id="form-create-college" onsubmit="handleCollegeCreate(event)" class="space-y-3 text-xs">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-400 mb-1">College Name</label>
                        <input type="text" id="col-name" required placeholder="IIT Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">Code (Unique)</label>
                        <input type="text" id="col-code" required placeholder="IITD-004" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-400 mb-1">City</label>
                        <input type="text" id="col-city" required placeholder="New Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">State</label>
                        <input type="text" id="col-state" required placeholder="Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-400 mb-1">Email</label>
                        <input type="email" id="col-email" required placeholder="contact@iitd.ac.in" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">Phone</label>
                        <input type="text" id="col-phone" placeholder="011-26597135" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Address</label>
                    <input type="text" id="col-address" required placeholder="Hauz Khas, New Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Logo Image (Optional)</label>
                    <input type="file" id="col-logo-input" accept="image/jpeg,image/png" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-white hover:file:bg-slate-700 cursor-pointer">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-create-college')" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-medium rounded-xl shadow-lg shadow-brand-500/20">
                        Save College
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 5: ADD COURSE TO COLLEGE -->
    <div id="modal-create-course" onclick="if(event.target === this) closeModal('modal-create-course')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
        <div class="glass-modal w-full max-w-md p-6 rounded-3xl space-y-5 shadow-2xl relative border border-slate-800 text-left max-h-[90vh] overflow-y-auto custom-scrollbar my-auto">
            <button onclick="closeModal('modal-create-course')" class="absolute top-4 right-4 text-slate-400 hover:text-white w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm transition-colors">✕</button>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-white">📚 Add New Course</h3>
                <p class="text-xs text-slate-400" id="create-course-college-name">Target College</p>
            </div>

            <form id="form-create-course" onsubmit="handleCourseCreate(event)" class="space-y-3 text-xs">
                <input type="hidden" id="course-target-college-id">
                <div>
                    <label class="block text-slate-400 mb-1">Course Name</label>
                    <input type="text" id="crs-name" required placeholder="B.Tech Artificial Intelligence" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-400 mb-1">Course Code</label>
                        <input type="text" id="crs-code" required placeholder="AI-101" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">Duration</label>
                        <input type="text" id="crs-duration" required placeholder="4 Years" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Total Available Seats</label>
                    <input type="number" id="crs-seats" min="1" required placeholder="10" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-brand-500">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-create-course')" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-xl shadow-lg shadow-emerald-500/20">
                        Create Course
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- CLIENT SIDE JAVASCRIPT API ENGINE & CONTROLLER -->
    <script>
        // Global Application State
        let state = {
            user: null,
            token: localStorage.getItem('sanctum_token') || '',
            activeTab: 'dashboard',
            colleges: [],
            applications: [],
            selectedApp: null,
            currentPage: 1,
            totalPages: 1,
            searchQuery: '',
            statusFilter: '',
            collegeFilter: '',
        };

        // Initialize App on DOM Load
        document.addEventListener('DOMContentLoaded', async () => {
            if (state.token) {
                await fetchCurrentUser();
            } else {
                updateUIState();
            }
            fetchStats();
            fetchColleges();
            fetchApplications();
        });

        // Toast Notification System
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colorClasses = {
                success: 'bg-emerald-950/90 border-emerald-500/40 text-emerald-200',
                error: 'bg-rose-950/90 border-rose-500/40 text-rose-200',
                info: 'bg-indigo-950/90 border-indigo-500/40 text-indigo-200',
                warning: 'bg-amber-950/90 border-amber-500/40 text-amber-200',
            }[type] || 'bg-slate-900 border-slate-700 text-slate-200';

            toast.className = `p-4 rounded-xl border shadow-xl backdrop-blur-md text-xs font-medium flex items-center gap-3 transition-all duration-300 transform translate-y-2 opacity-0 pointer-events-auto ${colorClasses}`;
            toast.innerHTML = `
                <div class="flex-1">${message}</div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white text-sm">✕</button>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Quick Role Login Handler
        async function quickLogin(email, password) {
            if (!email) return;
            showToast(`Logging in as ${email}...`, 'info');
            try {
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password }),
                });

                const data = await res.json();
                if (data.success) {
                    state.token = data.data.token;
                    state.user = data.data.user;
                    localStorage.setItem('sanctum_token', state.token);
                    showToast(`Logged in successfully as ${state.user.name} (${state.user.role})`, 'success');
                    updateUIState();
                    fetchStats();
                    fetchApplications(1);
                    fetchColleges();
                } else {
                    showToast(data.message || 'Login failed', 'error');
                }
            } catch (err) {
                showToast('Network error during login: ' + err.message, 'error');
            }
        }

        async function fetchCurrentUser() {
            try {
                const res = await fetch('/api/me', {
                    headers: { 
                        'Authorization': `Bearer ${state.token}`,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    state.user = data.data.user;
                } else {
                    logoutSilently();
                }
            } catch (err) {
                logoutSilently();
            }
            updateUIState();
        }

        function logoutSilently() {
            state.token = '';
            state.user = null;
            localStorage.removeItem('sanctum_token');
            updateUIState();
        }

        async function handleLogout() {
            if (state.token) {
                try {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: { 'Authorization': `Bearer ${state.token}`, 'Accept': 'application/json' }
                    });
                } catch (e) {}
            }
            logoutSilently();
            showToast('Successfully logged out.', 'info');
            fetchStats();
            fetchApplications(1);
        }

        // UI State Updates according to Logged-in User
        function updateUIState() {
            const authContainer = document.getElementById('auth-header-container');
            const roleBanner = document.getElementById('role-context-banner');
            const roleTitle = document.getElementById('role-title-text');
            const roleDesc = document.getElementById('role-desc-text');
            const roleBadge = document.getElementById('role-avatar-badge');
            const tabProfile = document.getElementById('tab-profile');
            const tabAudit = document.getElementById('tab-audit');
            const collegeAdminActions = document.getElementById('college-admin-actions');

            if (state.user) {
                const roleColors = {
                    ADMIN: 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                    COLLEGE_ADMIN: 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                    STUDENT: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                }[state.user.role] || 'bg-slate-800 text-slate-300';

                authContainer.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-bold text-white">${state.user.name}</div>
                            <span class="px-2 py-0.5 text-[10px] font-semibold border rounded-full ${roleColors}">
                                ${state.user.role}
                            </span>
                        </div>
                        <button onclick="handleLogout()" class="p-2 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl border border-slate-800 text-xs flex items-center gap-1">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </div>
                `;

                if (state.user.role === 'ADMIN') {
                    roleBadge.innerHTML = '👑';
                    roleTitle.innerHTML = `Logged in as System Admin (${state.user.name})`;
                    roleDesc.innerHTML = 'Full authorization: Manage all colleges, courses, search/approve/reject any student application, view AI summaries and audit logs.';
                    tabAudit.classList.remove('hidden');
                    tabProfile.classList.add('hidden');
                    collegeAdminActions.innerHTML = `
                        <button onclick="openModal('modal-create-college')" class="px-3.5 py-2 bg-brand-600 hover:bg-brand-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add New College
                        </button>
                    `;
                } else if (state.user.role === 'COLLEGE_ADMIN') {
                    roleBadge.innerHTML = '🏛️';
                    const collegeName = state.user.college ? state.user.college.name : 'Assigned Institute';
                    roleTitle.innerHTML = `Logged in as College Admin (${state.user.name} - ${collegeName})`;
                    roleDesc.innerHTML = `Authorized to view & manage student applications submitted to <strong>${collegeName}</strong>. AI summaries and seat management enabled.`;
                    tabAudit.classList.add('hidden');
                    tabProfile.classList.add('hidden');
                    collegeAdminActions.innerHTML = '';
                } else if (state.user.role === 'STUDENT') {
                    roleBadge.innerHTML = '🎓';
                    roleTitle.innerHTML = `Logged in as Student (${state.user.name})`;
                    roleDesc.innerHTML = 'Submit applications to available courses, monitor real-time admission status, and manage profile photo.';
                    tabAudit.classList.add('hidden');
                    tabProfile.classList.remove('hidden');
                    collegeAdminActions.innerHTML = '';
                    populateProfileFields();
                }
            } else {
                authContainer.innerHTML = `
                    <button onclick="openModal('modal-auth')" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Sign In / Register
                    </button>
                `;
                roleBadge.innerHTML = '👤';
                roleTitle.innerHTML = 'Guest Mode (Public Access)';
                roleDesc.innerHTML = 'Select any test account from the top banner to simulate Admin, College Admin, or Student permissions.';
                tabAudit.classList.add('hidden');
                tabProfile.classList.add('hidden');
                collegeAdminActions.innerHTML = '';
            }
        }

        // Navigation Tab Switcher
        function switchTab(tabId) {
            state.activeTab = tabId;
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.className = 'nav-btn px-4 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 flex items-center gap-2 transition-all';
            });
            const activeBtn = document.getElementById(`tab-${tabId}`);
            if (activeBtn) {
                activeBtn.className = 'nav-btn px-4 py-2 text-sm font-medium rounded-lg text-white bg-brand-600/20 text-brand-300 border border-brand-500/30 flex items-center gap-2 transition-all';
            }

            document.querySelectorAll('.tab-section').forEach(sec => sec.classList.add('hidden'));
            const activeSection = document.getElementById(`section-${tabId}`);
            if (activeSection) activeSection.classList.remove('hidden');

            if (tabId === 'applications') fetchApplications(1);
            if (tabId === 'audit') fetchAuditLogs(1);
            if (tabId === 'colleges') fetchColleges();
            if (tabId === 'dashboard') fetchStats();
        }

        // Dashboard Stats Fetcher
        async function fetchStats() {
            try {
                const headers = { 'Accept': 'application/json' };
                if (state.token) headers['Authorization'] = `Bearer ${state.token}`;

                const res = await fetch('/api/stats', { headers });
                const data = await res.json();

                if (data.success) {
                    const stats = data.data;
                    document.getElementById('stat-colleges-total').innerText = stats.colleges.total;
                    document.getElementById('stat-colleges-active').innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> ${stats.colleges.active} Active Institutes`;
                    
                    document.getElementById('stat-seats-avail').innerText = `${stats.courses.available_seats} / ${stats.courses.total_seats}`;
                    document.getElementById('stat-seats-detail').innerText = `${stats.courses.filled_seats} Seats Filled (${stats.courses.total} Total Courses)`;

                    document.getElementById('stat-apps-total').innerText = stats.applications.total;
                    document.getElementById('stat-apps-pending').innerText = `${stats.applications.submitted + stats.applications.under_review} Applications Under Review`;

                    document.getElementById('stat-apps-approved').innerText = stats.applications.approved;
                    document.getElementById('stat-apps-rejected').innerText = `${stats.applications.approved} Approved / ${stats.applications.rejected} Rejected`;
                }
            } catch (err) {}
        }

        // Colleges & Courses Fetcher
        async function fetchColleges() {
            const grid = document.getElementById('colleges-grid');
            const applyCollegeSelect = document.getElementById('apply-college-id');
            const filterCollegeSelect = document.getElementById('app-college-filter');

            try {
                const headers = { 'Accept': 'application/json' };
                if (state.token) headers['Authorization'] = `Bearer ${state.token}`;

                const res = await fetch('/api/colleges', { headers });
                const data = await res.json();

                if (data.success) {
                    state.colleges = data.data;
                    
                    // Render Colleges Grid
                    if (state.colleges.length === 0) {
                        grid.innerHTML = `<div class="col-span-full py-12 text-center text-slate-500 text-xs">No colleges registered yet.</div>`;
                        return;
                    }

                    grid.innerHTML = state.colleges.map(col => `
                        <div class="glass-card p-5 rounded-2xl space-y-4 border border-slate-800 hover:border-brand-500/40 transition-all flex flex-col justify-between group">
                            <div class="space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center overflow-hidden shrink-0 text-xl font-bold text-brand-400">
                                            ${col.logo ? `<img src="/storage/${col.logo}" class="w-full h-full object-cover">` : col.code.substring(0,2)}
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-white group-hover:text-brand-300 transition-colors">${col.name}</h3>
                                            <span class="text-[10px] font-mono text-slate-400">${col.code} • ${col.city}, ${col.state}</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold ${col.status === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400'} rounded-full">
                                        ${col.status}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-400 space-y-1">
                                    <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> ${col.address}</p>
                                    <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> ${col.email}</p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between gap-2">
                                <span class="text-xs font-medium text-slate-400">${col.courses_count || 0} Courses Offered</span>
                                <div class="flex items-center gap-2">
                                    ${state.user && state.user.role === 'ADMIN' ? `
                                        <button onclick="openAddCourseModal('${col.id}', '${col.name}')" class="px-2.5 py-1 text-xs bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg">
                                            + Course
                                        </button>
                                    ` : ''}
                                    <button onclick="viewCollegeCourses('${col.id}')" class="px-3 py-1 text-xs bg-brand-600/20 hover:bg-brand-600/40 text-brand-300 border border-brand-500/30 rounded-lg">
                                        View Courses →
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    // Populate Select Dropdowns
                    applyCollegeSelect.innerHTML = `<option value="">-- Choose College --</option>` + state.colleges.map(c => `<option value="${c.id}">${c.name} (${c.code})</option>`).join('');
                    filterCollegeSelect.innerHTML = `<option value="">All Colleges</option>` + state.colleges.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
                }
            } catch (err) {}
        }

        async function viewCollegeCourses(collegeId) {
            try {
                const res = await fetch(`/api/colleges/${collegeId}/courses`);
                const data = await res.json();
                if (data.success) {
                    const courses = data.data;
                    let content = `<div class="space-y-3">`;
                    if (courses.length === 0) {
                        content += `<p class="text-xs text-slate-500 py-4 text-center">No courses currently listed for this institute.</p>`;
                    } else {
                        courses.forEach(crs => {
                            const isAvailable = crs.available_seats > 0;
                            content += `
                                <div class="p-3 bg-slate-900 rounded-xl border border-slate-800 flex items-center justify-between gap-3 text-xs">
                                    <div>
                                        <h4 class="font-bold text-white">${crs.name} <span class="text-slate-500 font-mono text-[10px]">(${crs.code})</span></h4>
                                        <p class="text-[11px] text-slate-400">Duration: ${crs.duration}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border ${isAvailable ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20'}">
                                            ${crs.available_seats} / ${crs.total_seats} Seats Available
                                        </span>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    content += `</div>`;
                    showToast(content, 'info');
                }
            } catch (err) {}
        }

        // Applications Fetcher with Search & Pagination
        let searchTimeout = null;
        function debounceSearchApplications() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchApplications(1);
            }, 300);
        }

        async function fetchApplications(page = 1) {
            state.currentPage = page;
            const search = document.getElementById('app-search-input').value;
            const status = document.getElementById('app-status-filter').value;
            const collegeId = document.getElementById('app-college-filter').value;

            const tbody = document.getElementById('applications-tbody');
            tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-slate-500 text-xs">Fetching applications...</td></tr>`;

            try {
                const params = new URLSearchParams({ page });
                if (search) params.append('search', search);
                if (status) params.append('status', status);
                if (collegeId) params.append('college_id', collegeId);

                const headers = { 'Accept': 'application/json' };
                if (state.token) headers['Authorization'] = `Bearer ${state.token}`;

                const res = await fetch(`/api/applications?${params.toString()}`, { headers });
                const data = await res.json();

                if (data.success) {
                    const paginated = data.data;
                    state.applications = paginated.data || [];
                    state.totalPages = paginated.last_page || 1;

                    renderApplicationsTable();
                    renderPaginationControls(paginated);
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-rose-400 text-xs">Failed to load applications. Log in to view data.</td></tr>`;
            }
        }

        function renderApplicationsTable() {
            const tbody = document.getElementById('applications-tbody');

            if (state.applications.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="py-12 text-center text-slate-500 text-xs">No matching applications found.</td></tr>`;
                return;
            }

            const statusBadges = {
                SUBMITTED: 'bg-brand-500/10 text-brand-300 border-brand-500/30',
                UNDER_REVIEW: 'bg-amber-500/10 text-amber-300 border-amber-500/30',
                APPROVED: 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30',
                REJECTED: 'bg-rose-500/10 text-rose-300 border-rose-500/30',
            };

            tbody.innerHTML = state.applications.map(app => `
                <tr class="hover:bg-slate-900/50 transition-colors">
                    <td class="py-3.5 px-4 font-mono font-bold text-white">${app.application_no}</td>
                    <td class="py-3.5 px-4">
                        <div class="font-semibold text-white">${app.student ? app.student.name : 'Student'}</div>
                        <div class="text-[10px] text-slate-400 font-mono">${app.student ? app.student.registration_no : '-'}</div>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="text-white">${app.college ? app.college.name : '-'}</div>
                        <div class="text-[11px] text-brand-400">${app.course ? app.course.name : '-'}</div>
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="px-2.5 py-0.5 text-[10px] font-bold border rounded-full ${statusBadges[app.status] || 'bg-slate-800 text-slate-300'}">
                            ${app.status}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-slate-400 text-[11px]">
                        ${new Date(app.submitted_at || app.created_at).toLocaleDateString()}
                    </td>
                    <td class="py-3.5 px-4 text-right space-x-1">
                        <button onclick="openAppDetailModal('${app.id}')" class="px-2.5 py-1 text-xs bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg">
                            Review
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function renderPaginationControls(paginated) {
            const container = document.getElementById('app-pagination-container');
            container.innerHTML = `
                <div class="text-xs text-slate-400">
                    Showing Page <strong class="text-white">${paginated.current_page}</strong> of <strong class="text-white">${paginated.last_page}</strong> (${paginated.total} items)
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="fetchApplications(${paginated.current_page - 1})" ${paginated.current_page <= 1 ? 'disabled' : ''} class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-xs text-slate-300 rounded-lg disabled:opacity-40">Previous</button>
                    <button onclick="fetchApplications(${paginated.current_page + 1})" ${paginated.current_page >= paginated.last_page ? 'disabled' : ''} class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-xs text-slate-300 rounded-lg disabled:opacity-40">Next</button>
                </div>
            `;
        }

        function resetAppFilters() {
            document.getElementById('app-search-input').value = '';
            document.getElementById('app-status-filter').value = '';
            document.getElementById('app-college-filter').value = '';
            fetchApplications(1);
        }

        // Review & Details Modal Handler
        async function openAppDetailModal(appId) {
            state.selectedApp = state.applications.find(a => a.id == appId);
            if (!state.selectedApp) return;

            const app = state.selectedApp;
            document.getElementById('detail-app-no').innerText = app.application_no;
            document.getElementById('detail-student-name').innerText = app.student ? app.student.name : 'Student Details';
            
            document.getElementById('detail-student-fullname').innerText = app.student ? app.student.name : '-';
            document.getElementById('detail-student-email').innerText = app.student ? app.student.email : '-';
            document.getElementById('detail-student-mobile').innerText = app.student ? (app.student.mobile || 'N/A') : '-';
            document.getElementById('detail-student-gender').innerText = app.student ? (app.student.gender || 'N/A') : '-';

            document.getElementById('detail-college-name').innerText = app.college ? app.college.name : '-';
            document.getElementById('detail-course-name').innerText = app.course ? app.course.name : '-';
            document.getElementById('detail-course-duration').innerText = app.course ? app.course.duration : '-';
            document.getElementById('detail-course-seats').innerText = app.course ? `${app.course.available_seats} / ${app.course.total_seats} Available` : '-';

            document.getElementById('detail-remarks').innerText = app.remarks || 'No remarks provided.';

            const statusBadge = document.getElementById('detail-status-badge');
            statusBadge.className = `px-3 py-1 font-bold text-xs rounded-full border ${
                app.status === 'APPROVED' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30' :
                app.status === 'REJECTED' ? 'bg-rose-500/10 text-rose-300 border-rose-500/30' :
                app.status === 'SUBMITTED' ? 'bg-brand-500/10 text-brand-300 border-brand-500/30' : 'bg-amber-500/10 text-amber-300 border-amber-500/30'
            }`;
            statusBadge.innerText = app.status;

            // Rejection reason
            const rejBox = document.getElementById('detail-rejection-box');
            if (app.status === 'REJECTED' && app.remarks) {
                rejBox.classList.remove('hidden');
                document.getElementById('detail-rejection-text').innerText = app.remarks;
            } else {
                rejBox.classList.add('hidden');
            }

            // Reset AI Output Box
            document.getElementById('ai-summary-output-container').classList.add('hidden');

            // Admin / College Admin Decision Actions
            const actionsContainer = document.getElementById('detail-admin-actions');
            const canDecision = state.user && (state.user.role === 'ADMIN' || state.user.role === 'COLLEGE_ADMIN') && (app.status === 'SUBMITTED' || app.status === 'UNDER_REVIEW');

            if (canDecision) {
                actionsContainer.innerHTML = `
                    <button onclick="rejectApplication('${app.id}')" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-rose-500/20">
                        Reject Application
                    </button>
                    <button onclick="approveApplication('${app.id}')" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-emerald-500/20">
                        Approve & Allocate Seat
                    </button>
                `;
            } else {
                actionsContainer.innerHTML = `<span class="text-xs text-slate-500 italic">No decision actions required.</span>`;
            }

            openModal('modal-app-details');
        }

        // Trigger AI Application Summary Engine
        async function generateAISummary() {
            if (!state.selectedApp) return;
            const btn = document.getElementById('btn-trigger-ai');
            const output = document.getElementById('ai-summary-output-container');
            
            btn.innerHTML = `⏳ Analyzing...`;
            btn.disabled = true;

            try {
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                };
                if (state.token) {
                    headers['Authorization'] = `Bearer ${state.token}`;
                }

                const res = await fetch(`/api/applications/${state.selectedApp.id}/ai-summary`, {
                    method: 'POST',
                    headers: headers
                });

                const data = await res.json();
                if (data.success) {
                    const aiData = data.data.ai_analysis;
                    output.classList.remove('hidden');
                    output.innerHTML = `
                        <div class="p-3 bg-purple-950/80 rounded-xl border border-purple-500/40 text-purple-100 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-purple-300">Provider: ${aiData.provider || 'Groq AI Engine'}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-purple-500/20 text-purple-200 border border-purple-500/30 rounded-full">
                                    Rec: ${aiData.recommendation || 'REVIEW'}
                                </span>
                            </div>
                            <p class="leading-relaxed text-xs">${aiData.summary}</p>
                            ${aiData.risk_flags && aiData.risk_flags.length > 0 ? `
                                <div class="text-[11px] text-amber-300 font-semibold">Risk Flags: ${aiData.risk_flags.join(', ')}</div>
                            ` : ''}
                        </div>
                    `;
                    showToast('AI Application analysis generated successfully!', 'success');
                } else {
                    showToast(data.message || 'AI generation failed', 'error');
                }
            } catch (err) {
                showToast('Error requesting AI summary: ' + err.message, 'error');
            } finally {
                btn.innerHTML = `Generate AI Summary`;
                btn.disabled = false;
            }
        }

        // Approve Application Action
        async function approveApplication(appId) {
            if (!confirm('Approve this application? This will atomically allocate a seat in the course database.')) return;
            try {
                const res = await fetch(`/api/applications/${appId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ remarks: 'Application approved by admission committee.' })
                });

                const data = await res.json();
                if (data.success) {
                    showToast('Application APPROVED! Available course seats updated.', 'success');
                    closeModal('modal-app-details');
                    fetchApplications(state.currentPage);
                    fetchStats();
                    fetchColleges();
                } else {
                    showToast(data.message || 'Approval failed.', 'error');
                }
            } catch (err) {
                showToast('Error approving application: ' + err.message, 'error');
            }
        }

        // Reject Application Action
        async function rejectApplication(appId) {
            const reason = prompt('Enter rejection reason for student notification:', 'Does not meet eligibility minimum criteria');
            if (reason === null) return;

            try {
                const res = await fetch(`/api/applications/${appId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ reason })
                });

                const data = await res.json();
                if (data.success) {
                    showToast('Application REJECTED successfully.', 'warning');
                    closeModal('modal-app-details');
                    fetchApplications(state.currentPage);
                    fetchStats();
                } else {
                    showToast(data.message || 'Rejection failed.', 'error');
                }
            } catch (err) {
                showToast('Error rejecting application: ' + err.message, 'error');
            }
        }

        // Application Submission Form Handler
        function openApplyModal() {
            if (!state.user || state.user.role !== 'STUDENT') {
                showToast('Please log in as a Student to submit applications.', 'warning');
                quickLogin('student1@gmail.com', 'password');
                return;
            }
            openModal('modal-apply');
        }

        async function onApplyCollegeChange(collegeId) {
            const courseSelect = document.getElementById('apply-course-id');
            const seatChecker = document.getElementById('apply-seat-checker');
            seatChecker.classList.add('hidden');

            if (!collegeId) {
                courseSelect.innerHTML = `<option value="">-- Select College First --</option>`;
                return;
            }

            courseSelect.innerHTML = `<option value="">Loading courses...</option>`;
            try {
                const res = await fetch(`/api/colleges/${collegeId}/courses`);
                const data = await res.json();
                if (data.success) {
                    const courses = data.data;
                    if (courses.length === 0) {
                        courseSelect.innerHTML = `<option value="">No courses available</option>`;
                    } else {
                        courseSelect.innerHTML = `<option value="">-- Choose Course --</option>` + courses.map(c => `
                            <option value="${c.id}" data-seats="${c.available_seats}" data-totalseats="${c.total_seats}">${c.name} (${c.code}) - ${c.available_seats} seats left</option>
                        `).join('');
                    }
                }
            } catch (err) {}
        }

        function onApplyCourseChange(courseId) {
            const courseSelect = document.getElementById('apply-course-id');
            const selectedOption = courseSelect.options[courseSelect.selectedIndex];
            const seatChecker = document.getElementById('apply-seat-checker');

            if (courseId && selectedOption) {
                const seats = parseInt(selectedOption.getAttribute('data-seats') || 0);
                const totalSeats = parseInt(selectedOption.getAttribute('data-totalseats') || 0);
                
                seatChecker.classList.remove('hidden');
                if (seats > 0) {
                    seatChecker.className = 'p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-xs text-emerald-300 flex items-center justify-between';
                    seatChecker.innerHTML = `
                        <span>✓ Seats Available (${seats} of ${totalSeats} remaining)</span>
                        <span class="font-bold">STATUS: OK</span>
                    `;
                } else {
                    seatChecker.className = 'p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl text-xs text-rose-300 flex items-center justify-between';
                    seatChecker.innerHTML = `
                        <span>✕ Course Seats Full (0 remaining)</span>
                        <span class="font-bold">SUBMISSION BLOCKED</span>
                    `;
                }
            } else {
                seatChecker.classList.add('hidden');
            }
        }

        async function handleApplicationSubmit(e) {
            e.preventDefault();
            const college_id = document.getElementById('apply-college-id').value;
            const course_id = document.getElementById('apply-course-id').value;
            const remarks = document.getElementById('apply-remarks').value;

            try {
                const res = await fetch('/api/applications', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ college_id, course_id, remarks })
                });

                const data = await res.json();
                if (data.success) {
                    showToast('Application submitted successfully! Tracking APP #' + data.data.application_no, 'success');
                    closeModal('modal-apply');
                    switchTab('applications');
                    fetchStats();
                } else {
                    showToast(data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Submission failed'), 'error');
                }
            } catch (err) {
                showToast('Error submitting application: ' + err.message, 'error');
            }
        }

        // Student Profile Handler
        function populateProfileFields() {
            if (!state.user || !state.user.student) return;
            const s = state.user.student;
            document.getElementById('profile-reg-no-badge').innerText = 'Reg: ' + s.registration_no;
            document.getElementById('prof-name').value = s.name || '';
            document.getElementById('prof-email').value = s.email || '';
            document.getElementById('prof-mobile').value = s.mobile || '';
            document.getElementById('prof-dob').value = s.dob || '';
            document.getElementById('prof-gender').value = s.gender || 'Male';
            document.getElementById('prof-address').value = s.address || '';

            const img = document.getElementById('profile-preview-img');
            const placeholder = document.getElementById('profile-avatar-placeholder');
            if (s.profile_photo) {
                img.src = '/storage/' + s.profile_photo;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }

        function previewProfilePhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('profile-preview-img');
                    const placeholder = document.getElementById('profile-avatar-placeholder');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        async function saveProfile(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('name', document.getElementById('prof-name').value);
            formData.append('mobile', document.getElementById('prof-mobile').value);
            formData.append('dob', document.getElementById('prof-dob').value);
            formData.append('gender', document.getElementById('prof-gender').value);
            formData.append('address', document.getElementById('prof-address').value);

            const photoInput = document.getElementById('profile-photo-input');
            if (photoInput.files[0]) {
                formData.append('profile_photo', photoInput.files[0]);
            }

            try {
                const res = await fetch('/api/profile', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (data.success) {
                    showToast('Student profile and avatar updated successfully!', 'success');
                    await fetchCurrentUser();
                } else {
                    showToast(data.message || 'Profile update failed', 'error');
                }
            } catch (err) {
                showToast('Error saving profile: ' + err.message, 'error');
            }
        }

        // College & Course Creation (Admin)
        async function handleCollegeCreate(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('name', document.getElementById('col-name').value);
            formData.append('code', document.getElementById('col-code').value);
            formData.append('city', document.getElementById('col-city').value);
            formData.append('state', document.getElementById('col-state').value);
            formData.append('email', document.getElementById('col-email').value);
            formData.append('phone', document.getElementById('col-phone').value);
            formData.append('address', document.getElementById('col-address').value);

            const logo = document.getElementById('col-logo-input').files[0];
            if (logo) formData.append('logo', logo);

            try {
                const res = await fetch('/api/colleges', {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${state.token}`, 'Accept': 'application/json' },
                    body: formData
                });

                const data = await res.json();
                if (data.success) {
                    showToast('College created successfully!', 'success');
                    closeModal('modal-create-college');
                    fetchColleges();
                    fetchStats();
                } else {
                    showToast(data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'College creation failed'), 'error');
                }
            } catch (err) {
                showToast('Error creating college: ' + err.message, 'error');
            }
        }

        function openAddCourseModal(collegeId, collegeName) {
            document.getElementById('course-target-college-id').value = collegeId;
            document.getElementById('create-course-college-name').innerText = 'Target: ' + collegeName;
            openModal('modal-create-course');
        }

        async function handleCourseCreate(e) {
            e.preventDefault();
            const collegeId = document.getElementById('course-target-college-id').value;
            const body = {
                name: document.getElementById('crs-name').value,
                code: document.getElementById('crs-code').value,
                duration: document.getElementById('crs-duration').value,
                total_seats: parseInt(document.getElementById('crs-seats').value),
            };

            try {
                const res = await fetch(`/api/colleges/${collegeId}/courses`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${state.token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });

                const data = await res.json();
                if (data.success) {
                    showToast('New course created and seats initialized!', 'success');
                    closeModal('modal-create-course');
                    fetchColleges();
                    fetchStats();
                } else {
                    showToast(data.message || 'Course creation failed', 'error');
                }
            } catch (err) {
                showToast('Error creating course: ' + err.message, 'error');
            }
        }

        // Audit Logs Explorer (Admin Only)
        async function fetchAuditLogs(page = 1) {
            const tbody = document.getElementById('audit-tbody');
            tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-slate-500 text-xs">Loading audit trail...</td></tr>`;

            try {
                const res = await fetch(`/api/audit-logs?page=${page}`, {
                    headers: { 'Authorization': `Bearer ${state.token}`, 'Accept': 'application/json' }
                });

                const data = await res.json();
                if (data.success) {
                    const logs = data.data.data || [];
                    if (logs.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="6" class="py-12 text-center text-slate-500 text-xs">No audit logs recorded yet.</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = logs.map(l => `
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3 px-4 text-slate-400 text-[11px]">${new Date(l.created_at).toLocaleString()}</td>
                            <td class="py-3 px-4 font-semibold text-white">${l.user ? l.user.name : 'System'}</td>
                            <td class="py-3 px-4 font-mono font-bold text-brand-300 text-[11px]">${l.action}</td>
                            <td class="py-3 px-4 text-slate-300">${l.entity || '-'}</td>
                            <td class="py-3 px-4 font-mono text-slate-400">${l.entity_id || '-'}</td>
                            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">${l.ip_address || '127.0.0.1'}</td>
                        </tr>
                    `).join('');
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-rose-400 text-xs">Admin authorization required to view audit trail.</td></tr>`;
            }
        }

        // Auth Modal & Mode Controls
        function toggleAuthMode(mode) {
            const formLogin = document.getElementById('form-login');
            const formRegister = document.getElementById('form-register');
            const btnLogin = document.getElementById('btn-mode-login');
            const btnRegister = document.getElementById('btn-mode-register');

            if (mode === 'login') {
                formLogin.classList.remove('hidden');
                formRegister.classList.add('hidden');
                btnLogin.className = 'flex-1 py-2 text-xs font-semibold rounded-lg bg-brand-600 text-white transition-all';
                btnRegister.className = 'flex-1 py-2 text-xs font-semibold rounded-lg text-slate-400 hover:text-white transition-all';
            } else {
                formLogin.classList.add('hidden');
                formRegister.classList.remove('hidden');
                btnRegister.className = 'flex-1 py-2 text-xs font-semibold rounded-lg bg-emerald-600 text-white transition-all';
                btnLogin.className = 'flex-1 py-2 text-xs font-semibold rounded-lg text-slate-400 hover:text-white transition-all';
            }
        }

        async function handleLoginSubmit(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            await quickLogin(email, password);
            closeModal('modal-auth');
        }

        async function handleRegisterSubmit(e) {
            e.preventDefault();
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const mobile = document.getElementById('reg-mobile').value;
            const password = document.getElementById('reg-password').value;

            try {
                const res = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ name, email, mobile, password })
                });

                const data = await res.json();
                if (data.success) {
                    state.token = data.data.token;
                    state.user = data.data.user;
                    localStorage.setItem('sanctum_token', state.token);
                    showToast('Registration successful! Logged in as ' + name, 'success');
                    closeModal('modal-auth');
                    updateUIState();
                    fetchStats();
                } else {
                    showToast(data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Registration failed'), 'error');
                }
            } catch (err) {
                showToast('Error registering: ' + err.message, 'error');
            }
        }

        // Generic Modal Helpers
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</body>
</html>
