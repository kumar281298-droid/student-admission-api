<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduPortal AI') - Student Admission & College Management</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 400: '#818cf8',
                            500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 900: '#312e81'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .glass-modal { background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.12); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.3); border-radius: 4px; }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex flex-col font-sans antialiased custom-scrollbar overflow-x-hidden">

    <!-- Header & Quick Role Switcher Banner -->
    <header class="bg-slate-900/90 border-b border-slate-800 sticky top-0 z-40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 via-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-lg text-white tracking-tight">EduPortal <span class="text-brand-400">AI</span></span>
                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full">
                                ● System Live
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 hidden sm:block">Student Admission & College Management System</p>
                    </div>
                </a>

                <!-- Quick Role Switcher Demo Buttons -->
                <div class="hidden lg:flex items-center gap-2 bg-slate-950/80 p-1.5 rounded-xl border border-slate-800">
                    <span class="text-[11px] font-semibold text-slate-400 px-2">Quick Test Login:</span>
                    <form action="{{ route('login.post') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="admin@system.com">
                        <input type="hidden" name="password" value="password">
                        <button type="submit" class="px-2.5 py-1 text-xs font-medium bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 border border-purple-500/30 rounded-lg transition-all">👑 Admin</button>
                    </form>
                    <form action="{{ route('login.post') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="collegeadmin@delhiuniv.ac.in">
                        <input type="hidden" name="password" value="password">
                        <button type="submit" class="px-2.5 py-1 text-xs font-medium bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-lg transition-all">🏛️ College Admin</button>
                    </form>
                    <form action="{{ route('login.post') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="student1@gmail.com">
                        <input type="hidden" name="password" value="password">
                        <button type="submit" class="px-2.5 py-1 text-xs font-medium bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-lg transition-all">🎓 Student (Rahul)</button>
                    </form>
                </div>

                <!-- Auth Navigation -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-bold text-white">{{ Auth::user()->name }}</div>
                            <span class="px-2 py-0.5 text-[10px] font-semibold border rounded-full 
                                {{ Auth::user()->isAdmin() ? 'bg-purple-500/10 text-purple-300 border-purple-500/30' : (Auth::user()->isCollegeAdmin() ? 'bg-amber-500/10 text-amber-300 border-amber-500/30' : 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30') }}">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl border border-slate-800 text-xs flex items-center gap-1">
                                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-500 text-white font-medium text-xs rounded-xl shadow-lg shadow-brand-500/20">Sign In</a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs rounded-xl">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Role Dedicated Navbar -->
    <nav class="bg-slate-900 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between overflow-x-auto custom-scrollbar">
            <div class="flex space-x-1 py-2">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">📊 Admin Dashboard</a>
                        <a href="{{ route('admin.colleges') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('admin.colleges') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">🏛️ Colleges & Courses</a>
                        <a href="{{ route('admin.students') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('admin.students') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">🎓 Registered Students</a>
                        <a href="{{ route('admin.applications') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('admin.applications') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">📝 Applications & AI Review</a>
                        <a href="{{ route('admin.audit-logs') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('admin.audit-logs') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">📋 Audit Trail</a>
                    @elseif(Auth::user()->isCollegeAdmin())
                        <a href="{{ route('college_admin.dashboard') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('college_admin.dashboard') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">🏛️ College Dashboard</a>
                        <a href="{{ route('college_admin.applications') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('college_admin.applications') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">📝 College Applications & AI Review</a>
                    @elseif(Auth::user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('student.dashboard') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">🎓 Student Dashboard & Applications</a>
                        <a href="{{ route('student.apply') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('student.apply') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">📝 Apply for Admission</a>
                        <a href="{{ route('student.profile') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg {{ request()->routeIs('student.profile') ? 'bg-brand-600/20 text-brand-300 border border-brand-500/30' : 'text-slate-400 hover:text-white' }}">👤 My Profile & Photo Upload</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg text-slate-400 hover:text-white">Sign In</a>
                    <a href="{{ route('register') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg text-slate-400 hover:text-white">Student Registration</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Global Alert Banners (Validation & Error Messages) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if(session('success'))
            <div class="p-4 mb-4 bg-emerald-950/90 border border-emerald-500/40 rounded-2xl text-xs text-emerald-200 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">✕</button>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 mb-4 bg-amber-950/90 border border-amber-500/40 rounded-2xl text-xs text-amber-200 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span>{{ session('warning') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-amber-400 hover:text-white">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 bg-rose-950/90 border border-rose-500/40 rounded-2xl text-xs text-rose-200 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <span class="text-base">❌</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">✕</button>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 mb-4 bg-indigo-950/90 border border-indigo-500/40 rounded-2xl text-xs text-indigo-200 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <span class="text-base">ℹ️</span>
                    <span>{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-indigo-400 hover:text-white">✕</button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 bg-rose-950/90 border border-rose-500/50 rounded-2xl text-xs text-rose-200 space-y-1 shadow-lg">
                <div class="font-bold flex items-center gap-2 text-rose-300">
                    <span>⚠️ Form Validation Failed:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-slate-300 pl-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-950 border-t border-slate-900 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500 space-y-1">
            <p>Integrated Software Systems — Senior Laravel Developer Technical Assignment</p>
            <p class="text-[11px] text-slate-600">Built with Laravel 11, Blade, Sanctum, Role-Based Access Control, Atomic Seat Locks, and AI Application Summarization.</p>
        </div>
    </footer>

    @yield('scripts')
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>
