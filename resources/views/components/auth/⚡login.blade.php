<?php

use Livewire\Component;
use App\Models\Guards;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

new class extends Component {

    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        $guard = Guards::where('email', $this->email)->first();

        if (!$guard || $this->password !== $guard->password) {

            $this->addError('email', 'Invalid email or password.');
            return;
        }

        Session::put('guard_id', $guard->guard_id);
        Session::put('guard_name', $guard->name);
        Session::put('guard_email', $guard->email);

        return redirect()->route('dashboard');
    }
};
?>

<div class="min-vh-100 d-flex align-items-center justify-content-center px-3 py-4 position-relative overflow-hidden">

    {{-- Background Image --}}
    <img src="{{ asset('asset_pictures/Building.jpg') }}"
        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
        style="z-index: 0;">

    {{-- Red Overlay --}}
    <div class="position-absolute top-0 start-0 w-100 h-100"
        style="background: linear-gradient(135deg, rgba(139,0,0,0.75), rgba(90,0,0,0.70), rgba(0,0,0,0.60)); z-index: 1;">
    </div>

    {{-- Content --}}
    <div class="position-relative w-100" style="max-width: 460px; z-index: 2;">

        {{-- LOGIN CARD --}}
        <div class="bg-white rounded-4 overflow-hidden border"
            style="border-color: #E4D9D2 !important;
                    box-shadow: 0 1px 2px rgba(123,20,48,.06), 0 8px 32px rgba(123,20,48,.10), 0 24px 64px rgba(0,0,0,.06);">

            {{-- Banner --}}
            <div class="position-relative overflow-hidden bg-white" style="height: 110px;">

                {{-- Campus background --}}
                <img src="{{ asset('asset_pictures/Building.jpg') }}"
                    class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                    style="opacity: 0.08; z-index: 1;">

                {{-- Logo --}}
                <img src="{{ asset('asset_pictures/logo.jpg') }}"
                    class="position-absolute"
                    style="top: 8px; left: 20%; transform: translateX(-50%);
                    width: 40px; height: 40px; object-fit: contain; z-index: 3;">

                {{-- University Text --}}
                <div class="text-center mt-3 position-relative"
                    style="z-index: 3; font-family: 'League Spartan', sans-serif; line-height: 1.1;">

                    <p class="m-0 text-dark fw-bold"
                        style="font-size: 15px; font-weight: 800;">
                        BATANGAS STATE UNIVERSITY
                    </p>

                    <p class="m-0"
                        style="color: #d71920; font-size: 12px; font-weight: 700;">
                        The National Engineering University
                    </p>

                    <h1 class="m-0 text-dark mt-2"
                        style="font-size: 20px; font-family: 'Archivo Black', sans-serif; font-weight: 900;">
                        VIOLATION MONITORING SYSTEM
                    </h1>

                </div>

                {{-- Blue strip --}}
                <div class="position-absolute bottom-0 start-0 w-100 d-flex align-items-center justify-content-center"
                    style="height: 14px; background: #0085bd; z-index: 4;">

                    <p class="text-white m-0"
                        style="font-size: 8px; font-weight: 600;">
                        Leading Innovations, Transforming Lives, Building the Nation
                    </p>

                </div>

                {{-- Yellow accent --}}
                <div class="position-absolute bottom-0 end-0"
                    style="width: 120px; height: 22px; background: #f6b21a;
                    clip-path: polygon(25% 0, 100% 0, 100% 100%, 0 100%); z-index: 5;">
                </div>

                {{-- Red accent --}}
                <div class="position-absolute bottom-0 end-0"
                    style="width: 90px; height: 14px; background: #d71920;
                    clip-path: polygon(20% 0, 100% 0, 100% 100%, 0 100%); z-index: 6;">
                </div>

            </div>
            {{-- End Banner --}}

            {{-- Body --}}
            <div class="px-4 px-md-5 pt-4 pb-4">

                <h1 class="fw-bold"
                    style="font-size: 1.45rem; color: #1A0A0E;">
                    Welcome back
                </h1>

                <p style="font-size: 0.82rem; color: #7A6069;"
                    class="mt-1">
                    Sign in to your guard account to continue.
                </p>

                <hr class="mt-3 mb-4 opacity-100 rounded"
                    style="height: 2px; background: linear-gradient(to right, #F5C500, rgba(245,197,0,0.2)); border: none;">

                {{-- FORM --}}
                <form wire:submit.prevent="login">

                    {{-- EMAIL --}}
                    <div class="mb-3">

                        <input type="email"
                            wire:model="email"
                            placeholder="Email"
                            class="form-control @error('email') is-invalid @enderror"
                            style="height: 46px; background: #FEFCFB;
                            border: 1.5px solid {{ $errors->has('email') ? '#f87171' : '#E4D9D2' }};
                            border-radius: 10px; font-size: 14px;">

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-4">

                        <input type="password"
                            wire:model="password"
                            placeholder="Password"
                            class="form-control @error('password') is-invalid @enderror"
                            style="height: 46px; background: #FEFCFB;
                            border: 1.5px solid {{ $errors->has('password') ? '#f87171' : '#E4D9D2' }};
                            border-radius: 10px; font-size: 14px;">

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-center">

                        <button type="submit"
                            class="btn rounded-pill px-5 fw-semibold"
                            style="height: 42px; background: #F5C500; color: #5C0E22;
                            font-size: 14px; border: none; transition: all 0.15s;">

                            <span wire:loading.remove wire:target="login">
                                Sign In
                            </span>

                            <span wire:loading wire:target="login">
                                Signing in...
                            </span>

                        </button>

                    </div>

                </form>

            </div>
            {{-- End Body --}}

        </div>
        {{-- End Card --}}

    </div>
</div>