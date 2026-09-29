<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <nav class="navbar navbar-expand-lg shadow-sm px-3 px-md-4 py-2" style="background: linear-gradient(90deg, #000000, #272727); min-height: 82px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            {{-- Left: Logo + Title --}}
            <div class="d-flex align-items-center">
                <img
                    src="{{ asset('asset_pictures/logo.jpg') }}"
                    alt="BSU Logo"
                    class="me-3"
                    style="width: 60px; height: 60px; object-fit:border 1px solid #f1f1f1; border-radius: 50px; object-fit: contain; background-color: #f1f1f1;"
                >

                <div class="text-white">
                    <h1 class="mb-0 fw-bold" style="font-size: 1.25rem;">Batangas State University</h1>
                    <div style="font-size: 0.95rem; opacity: 0.9;">Student Monitoring System</div>
                </div>
            </div>

            {{-- Right: Time + User --}}
            <div class="d-flex align-items-center gap-3 text-white">
                <div class="text-end d-none d-md-block">
                    <div style="font-size: 0.72rem; opacity: 0.8;">Current Time</div>
                    <div id="current_time" class="fw-semibold" style="font-size: 0.92rem;">time</div>
                </div>
                @if(session()->has('guard_id'))
    <div class="dropdown">
        <button
            class="btn d-flex align-items-center px-2 py-1 border-0 dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            style="background: rgba(255,255,255,0.12); color: white; border-radius: 50px;"
        >
            <div
                class="rounded-circle d-flex align-items-center justify-content-center me-2"
                style="width: 36px; height: 36px; background: #f1f1f1; color: #000000; font-weight: 700;"
            >
                {{ strtoupper(substr(session('guard_name'), 0, 1)) }}
            </div>

            <span class="fw-medium">
                {{ session('guard_name') }}
            </span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px; min-width: 180px;">
            <li><a class="dropdown-item py-2" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2 text-danger" href="{{ route('guard.logout') }}">Logout</a></li>
        </ul>
    </div>
@endif
                </div>
            </div>

        </div>
    </nav>


    @script
        @vite(['resources/js/current_time.js'])
    @endscript
</div>