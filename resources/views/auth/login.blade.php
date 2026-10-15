<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SDVRS | Sign in</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F3F3F3] font-sans text-[#25282B]">
    <main class="min-h-screen lg:grid lg:grid-cols-2">
        <section class="relative hidden overflow-hidden bg-[#D7192D] px-10 py-10 text-white lg:flex lg:flex-col xl:px-20">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('pablo-borbon.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#D7192D] via-[#D7192D]/90 to-[#D7192D]/15"></div>
            <svg class="absolute inset-y-0 left-0 h-full w-[72%]" viewBox="0 0 720 1080" preserveAspectRatio="none" aria-hidden="true">
                <polygon points="0,0 190,0 300,190 190,380 0,380" fill="#a70f22" fill-opacity=".42"/>
                <polygon points="190,0 410,0 520,190 410,380 300,190" fill="#bd1428" fill-opacity=".50"/>
                <polygon points="0,380 190,380 300,570 190,760 0,760" fill="#bd1428" fill-opacity=".50"/>
                <polygon points="190,380 410,380 520,570 410,760 300,570" fill="#971021" fill-opacity=".42"/>
                <polygon points="410,0 600,0 720,190 600,380 520,190" fill="#8d0d1e" fill-opacity=".30"/>
                <polygon points="410,380 600,380 720,570 600,760 520,570" fill="#a70f22" fill-opacity=".34"/>
                <polygon points="0,760 190,760 300,950 190,1080 0,1080" fill="#971021" fill-opacity=".35"/>
                <polygon points="190,760 410,760 520,950 440,1080 190,1080 300,950" fill="#bd1428" fill-opacity=".38"/>
            </svg>
            <svg class="absolute -left-10 -top-8 h-[38rem] w-[38rem] text-[#ff9da7]/40" viewBox="0 0 600 600" fill="none" aria-hidden="true"><g stroke="currentColor" stroke-width="2"><path d="m50 40 70 40v80l-70 40-70-40V80l70-40Zm140 80 70 40v80l-70 40-70-40v-80l70-40Zm140-80 70 40v80l-70 40-70-40V80l70-40Zm140 80 70 40v80l-70 40-70-40v-80l70-40ZM50 280l70 40v80l-70 40-70-40v-80l70-40Zm140 80 70 40v80l-70 40-70-40v-80l70-40Zm140-80 70 40v80l-70 40-70-40v-80l70-40Z"/></g><g fill="currentColor"><circle cx="120" cy="80" r="5"/><circle cx="260" cy="160" r="5"/><circle cx="400" cy="80" r="5"/><circle cx="540" cy="160" r="5"/></g></svg>
            <div class="absolute left-0 top-0 h-56 w-40 bg-[#D7192D]/50 [clip-path:polygon(0_0,100%_0,58%_100%,0_100%)]"></div>
            <div class="absolute inset-x-0 bottom-0 h-2 bg-[#FFFFFF]"></div>
            <div class="relative flex items-center gap-4">
                <img src="{{ asset('Batangas_State_Logo.png') }}" alt="Batangas State University logo" class="h-16 w-16 object-contain">
                <div><p class="text-2xl font-black tracking-tight">SDVRS</p><p class="mt-1 text-sm text-white/80">Student Violation Recording System</p></div>
            </div>
            <div class="relative my-auto max-w-xl py-16">
                <div>
                    <p class="text-sm font-bold tracking-[0.24em] text-white">BATANGAS STATE UNIVERSITY</p>
                    <p class="mt-2 text-sm font-semibold tracking-wide text-white/85">ARASOF-Nasugbu Campus</p>
                </div>
                <h1 class="mt-10 text-5xl font-bold leading-[1.08] tracking-tight xl:text-6xl" style="font-family: 'Times New Roman', Times, serif;">A safer campus through responsible action.</h1>
                <div class="mt-7 h-1 w-24 bg-[#FFFFFF]"></div>
                <p class="mt-9 max-w-lg text-lg leading-8 text-white/90">A secure system for recording, reviewing, and tracking student violations with fairness, care, and accountability.</p>
                <ul class="mt-14 space-y-5 text-base text-red-50">
                    <li class="flex items-center gap-4"><span class="h-3 w-3 shrink-0 rounded-full bg-[#FFFFFF] ring-8 ring-white/10"></span>Record incidents accurately and consistently.</li>
                    <li class="flex items-center gap-4"><span class="h-3 w-3 shrink-0 rounded-full bg-[#FFFFFF] ring-8 ring-white/10"></span>Monitor violation cases from report to resolution.</li>
                    <li class="flex items-center gap-4"><span class="h-3 w-3 shrink-0 rounded-full bg-[#FFFFFF] ring-8 ring-white/10"></span>Keep student records organized and traceable.</li>
                </ul>
            </div>
            <div class="relative rounded-2xl border border-white/15 bg-white/10 px-5 py-4 text-sm text-red-50">Your session is protected and managed securely by SDVRS.</div>
        </section>
        <section class="flex items-center justify-center bg-[#F3F3F3] px-5 py-10 sm:px-10">
            <div class="w-full max-w-xl border-t-4 border-[#D7192D] bg-white px-7 py-10 shadow-xl shadow-neutral-300/40 sm:px-12 sm:py-12">
                <div class="grid h-16 w-16 place-items-center rounded-full border border-[#FFFFFF] bg-[#F3F3F3] text-[#D7192D]"><svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3m-4 4v2"/></svg></div>
                <p class="mt-9 text-sm font-bold tracking-[0.22em] text-[#D7192D]">SDVRS PORTAL</p>
                <h2 class="mt-4 font-serif text-4xl font-bold tracking-tight text-[#25282B]">Welcome back</h2>
                <p class="mt-5 max-w-md text-lg leading-8 text-slate-500">Sign in to access the Student Violation Recording and Tracking System.</p>
                <form method="POST" action="{{ route('login.store') }}" class="mt-10 space-y-6">
                    @csrf
                    @error('email')<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ $message }}</div>@enderror
                    <label class="block text-base font-bold text-slate-800">Username<span class="relative mt-2 block"><svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" d="m3 7 9 6 9-6"/></svg><input required name="email" type="text" value="{{ old('email') }}" autocomplete="username" placeholder="Enter your username" class="block w-full rounded-xl border border-slate-200 py-4 pl-12 pr-4 text-base text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-red-500 focus:ring-4 focus:ring-red-100"></span></label>
                    <label class="block text-base font-bold text-slate-800">Password<span class="relative mt-2 block"><svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3"/></svg><input required name="password" type="password" autocomplete="current-password" placeholder="Enter your password" class="block w-full rounded-xl border border-slate-200 py-4 pl-12 pr-4 text-base text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-red-500 focus:ring-4 focus:ring-red-100"></span></label>
                    <button class="flex w-full items-center justify-center gap-3 bg-[#D7192D] px-4 py-4 text-base font-bold text-white shadow-lg shadow-red-700/20 transition hover:bg-[#b81829] focus:outline-none focus:ring-4 focus:ring-red-200"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4m6 2c0 5-4 9-9 9s-9-4-9-9 4-9 9-9 9 4 9 9Z"/></svg>Sign in to SDVRS</button>
                </form>
                <p class="mx-auto mt-9 max-w-sm text-center text-sm leading-6 text-slate-500">For security, your session expires after a period of inactivity. Contact the system administrator if you cannot access your account.</p>
            </div>
        </section>
    </main>
</body>
</html>
