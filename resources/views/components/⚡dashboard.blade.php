<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Students;
use App\Models\Records;
use App\Models\Violations;
use App\Services\GPIOService;
use App\Models\Suspensions;
use Illuminate\Support\Facades\DB;

new class extends Component {

    public $angle = 0;


    public string $sr_code = '';
    public $scanned = '';
    public string $student_name = '';
    public int $student_id = 0;

    // Violation form fields (separate from scanner)
    public string $vio_sr_code = '';
    public string $vio_guard = '';
    public string $vio_location = '';
    public string $vio_notes = '';
    public string $vio_violation_id = '';

    public bool $show_confirm = false;

    public function show_student($code)
    {
        $this->sr_code = $code;
        $this->scanned = $code;
    }

    #[Computed]
    public function students()
    {
        return Students::where('sr_code', $this->sr_code)
            ->select('*')
            ->first();
    }

    #[Computed]
    public function violations()
    {
        $student = $this->students;
        if (!$student) {
            return collect();
        }
        return Records::with(['students', 'violations'])
            ->where('student_id', $student->student_id)
            ->get();
    }

    #[Computed]
    public function suspensions()
    {
        $student = $this->students;
        if (!$student) {
            return collect();
        }
        return DB::table('suspensions')
            ->join('records', 'suspensions.record_id', '=', 'records.record_id')
            ->join('students', 'records.student_id', '=', 'students.student_id')
            ->where('students.student_id', $student->student_id)
            ->select('students.*', 'records.*', 'suspensions.*')
            ->get();

    }

    #[Computed]
    public function violation_list()
    {
        return App\Models\Violations::with(['violation_descriptions'])
            ->whereIn('violation_id', [1, 3, 5, 6, 21, 17, 16, 12, 9, 22])
            ->get();
    }
    public function consecutive_minor(int $record_id)
    {
        $record = DB::table('records')
            ->join('students', 'records.student_id', '=', 'students.student_id')
            ->join('violations', 'records.violation_id', '=', 'violations.violation_id')
            ->where('sr_code', $this->vio_sr_code)
            ->where('violation_weight', 'MINOR')
            ->count();

        if ($record > 0 && $record % 3 === 0) {
            Suspensions::create([
                'record_id' => $record_id,
                'num_of_days_suspended' => 3,
            ]);
        }
    }
    public function record_suspension()
    {

        $set_a = [9];
        $set_b = [12];
        $set_c = [16, 17];
        $set_e = [21, 22];

        $record = DB::table('records')
            ->join('students', 'records.student_id', '=', 'students.student_id')
            ->join('violations', 'records.violation_id', '=', 'violations.violation_id')
            ->where('sr_code', $this->vio_sr_code)
            ->where('violations.violation_id', $this->vio_violation_id)
            ->count();
        $record_id = Records::latest()->first()->record_id;

        // Set A

        if (in_array($this->vio_violation_id, $set_a)) {
            if ($record === 1) {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 3,
                ]);
            } else {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 4,
                ]);
            }
        } elseif (in_array($this->vio_violation_id, $set_b)) {
            if ($record === 1) {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 5,
                ]);
            } else {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 7,
                ]);
            }
        } elseif (in_array($this->vio_violation_id, $set_c)) {
            if ($record === 1) {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 6,
                ]);
            } else {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 8,
                ]);
            }
        } elseif (in_array($this->vio_violation_id, $set_e)) {
            if ($record === 1) {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 9,
                ]);
            } else {
                Suspensions::create([
                    'record_id' => $record_id,
                    'num_of_days_suspended' => 12,
                ]);
            }
        }

    }

    public function confirm_violation()
    {
        $this->validate([
            'vio_sr_code' => 'required',
            'vio_violation_id' => 'required',
        ], [
            'vio_sr_code.required' => 'SR-Code is required.',
            'vio_violation_id.required' => 'Please select a violation.',
        ]);

        $student = Students::where('sr_code', $this->vio_sr_code)->first();

        if (!$student) {
            $this->dispatch(
                'show-toast',
                message: 'SR-Code not found. Please check and try again.',
                type: 'error'
            );
            return;
        }

        $this->show_confirm = true;
    }

    public function record_violation()
    {
        $student = Students::where('sr_code', $this->vio_sr_code)
            ->select('student_id')
            ->first();

        if (!$student) {
            $this->show_confirm = false;
            $this->dispatch(
                'show-toast',
                message: 'SR-Code not found.',
                type: 'error'
            );
            return;
        }

        $newRecord = Records::create([
            'student_id' => $student->student_id,
            'violation_id' => (int) $this->vio_violation_id,
        ]);


        $this->consecutive_minor($newRecord->record_id);
        $this->record_suspension();

        $this->show_confirm = false;
        $this->reset(['vio_sr_code', 'vio_violation_id', 'vio_guard', 'vio_notes', 'vio_location']);
        $this->dispatch(
            'show-toast',
            message: 'Violation recorded successfully.',
            type: 'success'
        );


        // dd($count_records);
    }

    public function cancel_confirm()
    {
        $this->show_confirm = false;
    }

    public function show_suspension()
    {

    }
    public function success_entry($value): void
    {
        $this->scanned = $value;

        if ($this->student && !$this->isSuspended) {
            $this->open();
        }
    }



    #[Computed]
    public function daysLeft()
    {
        if (!$this->suspension) return 0;

        $end = \Carbon\Carbon::parse($this->suspension->created_at)
            ->addDays($this->suspension->num_of_days_suspended);

        return max(0, now()->startOfDay()->diffInDays($end->startOfDay(), false));
    }
    #[Computed]
    public function isSuspended()
    {
        if (!$this->suspension) return false;

        $end = \Carbon\Carbon::parse($this->suspension->created_at)
            ->addDays($this->suspension->num_of_days_suspended);

        return now()->lt($end);
    }
   

    public function open()
    {

        GPIOService::move(18, 180);
        $this->angle = 180;
    }
};
?>

<div class="container-fluid py-4" style="background:#f0efeb; min-height:100vh;">

    <p class="fw-semibold mb-3" style="font-size:14px;">Student Information</p>

    {{-- ── MAIN LAYOUT: info left + violation panel right ── --}}
    <div class="row g-3 align-items-start">

        {{-- ════════════════ LEFT SIDE ════════════════ --}}
        <div class="col-xl-8 d-flex flex-column gap-3">

            {{-- ── TOP ROW: camera · student · records ── --}}
            <div class="row g-3">

                {{-- CAMERA --}}
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div
                            class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                            <span class="fw-semibold" style="font-size:13px;">Live Camera Feed</span>
                            <span class="badge rounded-pill bg-success" style="font-size:10px;">LIVE</span>
                        </div>
                        <div class="card-body pt-2">
                            <div class="rounded-3 overflow-hidden mb-3" style="background:#1c1c1e; height:180px;">
                                <video id="video" style="width:100%; height:100%; object-fit:cover;" autoplay
                                    playsinline muted></video>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="text-muted" style="font-size:12px;">Entry Status:</span>
                                <span class="badge rounded-pill px-3 py-1"
                                    style="background:#dcfce7; color:#166534; font-size:11px;">Allowed</span>
                            </div>

                            <input id="result_data" class="form-control form-control-sm mb-1" placeholder="Scan SR-Code"
                                wire:model.live="sr_code"
                                style="font-family:monospace; background:#f5f4f0; border-color:#ddd;">
                            <small class="text-muted" style="font-family:monospace; font-size:11px;">
                                Detected: {{ $sr_code }}
                            </small>
                        </div>
                    </div>
                </div>

                {{-- STUDENT DETAILS --}}
                <div class="col-md-5">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-3">
                            <span class="fw-semibold" style="font-size:13px;">Student Details</span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-start">
                                <img src="{{ asset('storage/asset_pictures/people-default.svg') }}"
                                    class="rounded-circle border flex-shrink-0"
                                    style="width:72px; height:72px; object-fit:cover;" alt="Student photo">
                                <div class="flex-fill">
                                    <label class="text-muted mb-1 d-block" style="font-size:11px;">Student Name</label>
                                    <div class="form-control form-control-sm bg-light mb-2 border-0"
                                        style="font-size:13px;">
                                        {{ $this->students->student_name ?? 'No Student Record' }}
                                    </div>

                                    <label class="text-muted mb-1 d-block" style="font-size:11px;">SR-Code</label>
                                    <div class="form-control form-control-sm bg-light mb-2 border-0"
                                        style="font-size:13px; font-family:monospace;">
                                        {{ $this->sr_code ?: '—' }}
                                    </div>

                                    <label class="text-muted mb-1 d-block" style="font-size:11px;">Program</label>
                                    <div class="form-control form-control-sm bg-light border-0" style="font-size:13px;">
                                        {{ $this->students->Programs->program ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RECORDS SUMMARY --}}
                <div class="col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-3">
                            <span class="fw-semibold" style="font-size:13px;">Records</span>
                        </div>
                        <div class="card-body text-center">
                            <div class="py-3 border-bottom">
                                <div class="text-danger fw-bold lh-1 mb-1" style="font-size:36px; letter-spacing:-2px;">
                                    {{ $this->violations->count() }}
                                </div>
                                <small class="text-muted">Violations</small>
                            </div>
                            <div class="py-3">
                                <div class="fw-bold lh-1 mb-1"
                                    style="font-size:36px; letter-spacing:-2px; color:#f59e0b;">
                                    {{ $this->suspensions->count() }}
                                </div>
                                <small class="text-muted">Suspensions</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end top row --}}

            {{-- ── VIOLATION RECORDS TABLE ── --}}
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                    <span class="fw-semibold" style="font-size:13px;">Violation Records</span>
                    @if($this->violations->count() > 0)
                        <span class="badge rounded-pill bg-danger" style="font-size:11px;">
                            {{ $this->violations->count() }} Violation{{ $this->violations->count() !== 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted fw-normal ps-3" style="font-size:11px;">#</th>
                                <th class="text-muted fw-normal" style="font-size:11px;">Date</th>
                                <th class="text-muted fw-normal" style="font-size:11px;">Type</th>
                                <th class="text-muted fw-normal" style="font-size:11px;">Violation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->violations as $record)
                                                    <tr>
                                                        <td class="text-muted ps-3" style="font-family:monospace; font-size:12px;">{{
                                $loop->iteration }}</td>
                                                        <td class="text-muted" style="font-family:monospace; font-size:12px;">{{
                                $record->created_at }}</td>
                                                        <td>
                                                            @php $w = strtolower($record->violations->violation_weight ?? '') @endphp
                                                            @if($w === 'minor')
                                                                <span class="badge rounded-pill px-2"
                                                                    style="background:#FEF9C3; color:#854D0E; font-size:10px;">MINOR</span>
                                                            @elseif($w === 'major set a')
                                                                <span class="badge rounded-pill px-2"
                                                                    style="background:#FEE2E2; color:#991B1B; font-size:10px;">MAJOR Set A</span>
                                                            @elseif($w === 'major set b')
                                                                <span class="badge rounded-pill px-2"
                                                                    style="background:#FCA5A5; color:#7F1D1D; font-size:10px;">MAJOR Set B</span>
                                                            @elseif($w === 'major set c')
                                                                <span class="badge rounded-pill px-2"
                                                                    style="background:#F87171; color:#7F1D1D; font-size:10px;">MAJOR Set C</span>
                                                            @elseif($w === 'major set d')
                                                                <span class="badge rounded-pill px-2"
                                                                    style="background:#EF4444; color:#FFFFFF; font-size:10px;">MAJOR Set D</span>
                                                            @elseif($w === 'major set e')
                                                                <span class="badge rounded-pill px-2"
                                                                    style="background:#DC2626; color:#FFFFFF; font-size:10px;">MAJOR Set E</span>
                                                            @else
                                                                <span class="badge rounded-pill px-2"
                                                                    style="background:#991B1B; color:#FFFFFF; font-size:10px;">MAJOR Set F</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $record->violations->violation_name }}</td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5" style="font-size:13px;">
                                        No violation records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Suspensions --}}
            {{-- Suspensions --}}
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                    <span class="fw-semibold" style="font-size:13px;">Suspensions</span>
                    @if($this->suspensions->count() > 0)
                        <span class="badge rounded-pill bg-warning text-dark" style="font-size:11px;">
                            {{ $this->suspensions->count() }} Suspension{{ $this->suspensions->count() !== 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted fw-normal ps-3" style="font-size:11px;">#</th>
                                <th class="text-muted fw-normal" style="font-size:11px;">Date</th>
                                <th class="text-muted fw-normal" style="font-size:11px;">Days Suspended</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->suspensions as $suspension)
                                <tr>
                                    <td class="text-muted ps-3" style="font-family:monospace; font-size:12px;">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="text-muted" style="font-family:monospace; font-size:12px;">
                                        {{ $suspension->created_at }}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-2"
                                            style="background:#FEF9C3; color:#854D0E; font-size:10px;">
                                            {{ $suspension->num_of_days_suspended }} days
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5" style="font-size:13px;">
                                        No suspensions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end left side --}}

        {{-- ════════════════ RIGHT SIDE — VIOLATION PANEL ════════════════ --}}
        <div class="col-xl-4">
            <div class="card border-0 rounded-4 shadow-sm h-100" style="border-left: 3px solid #ef4444 !important;">

                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span
                            style="width:8px; height:8px; border-radius:50%; background:#ef4444; display:inline-block; flex-shrink:0;"></span>
                        <span class="fw-semibold" style="font-size:13px;">Record Violation</span>
                    </div>
                    <span class="badge rounded-pill px-2"
                        style="background:#fee2e2; color:#991b1b; font-size:10px;">Guard Panel</span>
                </div>

                <div class="card-body pt-2">

                    {{-- Student strip (reflects scanner, read-only preview) --}}
                    <div class="rounded-3 p-3 mb-3 d-flex align-items-center gap-2" style="background:#f5f4f0;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-semibold"
                            style="width:38px; height:38px; background:#dbeafe; color:#1d4ed8; font-size:12px;">
                            {{ strtoupper(substr($this->students->student_name ?? 'S', 0, 2)) }}
                        </div>
                        <div class="flex-fill overflow-hidden">
                            <div class="fw-semibold text-truncate" style="font-size:13px;">
                                {{ $this->students->student_name ?? 'No student scanned yet' }}
                            </div>
                            <div class="text-muted text-truncate" style="font-size:11px; font-family:monospace;">
                                {{ $sr_code ?: '—' }}
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="text-muted" style="font-size:10px;">Prior</div>
                            <div class="fw-bold text-danger" style="font-size:15px;">{{ $this->violations->count() }}
                            </div>
                        </div>
                    </div>

                    {{-- SR-Code — manual input, NOT tied to scanner --}}
                    <div class="mb-3">
                        <label class="form-label text-muted mb-1" style="font-size:11px;">SR-Code of Violator</label>
                        <input type="text"
                            class="form-control form-control-sm @error('vio_sr_code') is-invalid @enderror"
                            wire:model.live="vio_sr_code" placeholder="Type SR-Code manually e.g. 23-77394"
                            maxlength="8"
                            style="font-family:monospace; background:#f5f4f0; border-color:#ddd; font-size:13px;">
                        @error('vio_sr_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" style="font-size:10px; color:#aaa;">Independent from the scanner above.
                        </div>
                    </div>

                    {{-- Date & Time --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted mb-1" style="font-size:11px;">Date</label>
                            <input type="date" class="form-control form-control-sm" id="vio-date"
                                style="background:#f5f4f0; border-color:#ddd; font-size:13px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted mb-1" style="font-size:11px;">Time</label>
                            <input type="time" class="form-control form-control-sm" id="vio-time"
                                style="background:#f5f4f0; border-color:#ddd; font-size:13px;">
                        </div>
                    </div>

                    {{-- Violation --}}
                    <div class="mb-3">
                        <label class="form-label text-muted mb-1" style="font-size:11px;">Violation</label>
                        <select class="form-select form-select-sm @error('vio_violation_id') is-invalid @enderror"
                            wire:model.live="vio_violation_id"
                            style="background:#f5f4f0; border-color:#ddd; font-size:13px;">
                            <option value="">— Select violation —</option>
                            @foreach($this->violation_list as $v)
                                <option value="{{ $v->violation_id }}">
                                    {{ $v->violation_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('vio_violation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Description hint --}}
                        @php
                            $selected = $this->violation_list->firstWhere('violation_id', $vio_violation_id);
                        @endphp
                        @if($selected && $selected->violation_descriptions->first())
                            <div class="mt-1 px-2 py-2 rounded-2 d-flex gap-2 align-items-start"
                                style="background:#fffbeb; border:0.5px solid #fcd34d; font-size:11px; color:#92400e; line-height:1.5;">
                                <span style="flex-shrink:0;">ℹ️</span>
                                <span>{{ $selected->violation_descriptions->first()->description }}</span>
                            </div>
                        @endif
                    </div>


                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <button type="button" wire:click="confirm_violation" wire:loading.attr="disabled"
                            class="btn btn-danger btn-sm fw-semibold">
                            <span wire:loading wire:target="confirm_violation"
                                class="spinner-border spinner-border-sm me-1"></span>
                            Submit violation
                        </button>
                        <button type="button" class="btn btn-light btn-sm border"
                            wire:click="$set('vio_sr_code', ''); $set('vio_violation_id', ''); $set('vio_guard', ''); $set('vio_notes', ''); $set('vio_location', '');">
                            Clear
                        </button>
                    </div>

                </div>
            </div>
        </div>{{-- end right panel --}}

    </div>{{-- end main layout --}}

    {{-- ── CONFIRMATION DIALOG ── --}}
    @if($show_confirm)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="background:rgba(0,0,0,0.45); z-index:9999;">
            <div class="card border-0 rounded-4 shadow-lg p-4" style="width:360px; max-width:95vw;">

                <div class="d-flex align-items-center gap-2 mb-3">
                    <span
                        style="width:10px; height:10px; border-radius:50%; background:#ef4444; display:inline-block;"></span>
                    <span class="fw-semibold" style="font-size:14px;">Confirm Violation</span>
                </div>

                <div class="rounded-3 p-3 mb-3" style="background:#f5f4f0;">
                    <div class="text-muted mb-1" style="font-size:11px;">Student</div>
                    <div class="fw-semibold" style="font-size:13px;">
                        {{ Students::where('sr_code', $vio_sr_code)->first()?->student_name ?? $vio_sr_code }}
                    </div>

                    <div class="text-muted mt-2 mb-1" style="font-size:11px;">Violation</div>
                    <div class="fw-semibold" style="font-size:13px;">
                        {{ $this->violation_list->firstWhere('violation_id', $vio_violation_id)?->violation_name ?? '—' }}
                    </div>
                </div>

                <p class="text-muted mb-3" style="font-size:12px;">
                    Are you sure you want to record this violation? This action cannot be undone.
                </p>

                <div class="d-grid gap-2">
                    <button class="btn btn-danger btn-sm fw-semibold" wire:click="record_violation"
                        wire:loading.attr="disabled">
                        <span wire:loading wire:target="record_violation"
                            class="spinner-border spinner-border-sm me-1"></span>
                        Yes, record violation
                    </button>
                    <button class="btn btn-light btn-sm border" wire:click="cancel_confirm">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- ── TOAST ── --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:10000;">
        <div id="lw-toast-inner" class="toast align-items-center border-0 text-white" role="alert" aria-live="assertive"
            aria-atomic="true" style="min-width:280px;">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2" style="font-size:13px;" id="lw-toast-body">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

</div>

@script
<script>
    // Auto-fill date & time
    (function () {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        const dateEl = document.getElementById('vio-date');
        const timeEl = document.getElementById('vio-time');
        if (dateEl) dateEl.value = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate());
        if (timeEl) timeEl.value = pad(now.getHours()) + ':' + pad(now.getMinutes());
    })();

    $wire.on('show-toast', ({ message, type }) => {
        const el = document.getElementById('lw-toast-inner');
        const body = document.getElementById('lw-toast-body');
        if (!el || !body) return;

        const isSuccess = type === 'success';
        el.style.background = isSuccess ? '#22c55e' : '#ef4444';
        body.innerHTML = (isSuccess ? '✅ ' : '❌ ') + message;

        const existing = bootstrap.Toast.getInstance(el);
        if (existing) existing.dispose();

        const toast = new bootstrap.Toast(el, { delay: 3500 });
        toast.show();
    });
</script>
@vite(['resources/js/scanner.js'])
@endscript