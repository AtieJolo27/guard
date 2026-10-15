<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

use App\Models\Students;
use App\Models\Records;
use App\Models\Violations;
use App\Services\GPIOService;
use App\Models\Suspensions;
use App\Mail\ViolationRecorded;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

new class extends Component {

    public $angle = 0;

    public string $sr_code = '';
    public $scanned = '';
    public string $student_name = '';
    public int $student_id = 0;

    // Violation form fields
    public string $vio_sr_code = '';
    public string $vio_guard = '';
    public string $vio_location = '';
    public string $vio_notes = '';
    public string $vio_violation_id = '';

    public bool $show_confirm = false;

    public function lookup_student(string $code): void
    {
        $this->resetValidation();
        $this->reset('vio_violation_id', 'show_confirm');
        $this->sr_code = trim($code);
        unset($this->students, $this->violations, $this->suspensions, $this->suspension, $this->isSuspended);
        $this->vio_sr_code = $this->students ? $this->sr_code : '';
        if (!$this->students) {
            $this->addError('sr_code', 'No student found. Check your SR code and try again.');
        }
    }

    public function reset_student(): void
    {
        $this->reset('sr_code', 'vio_sr_code', 'vio_violation_id', 'show_confirm', 'scanned');
        $this->resetValidation();
    }

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
    public function suspension()
    {
        $student = $this->students;

        if (!$student) {
            return null;
        }

        return DB::table('suspensions')
            ->join('records', 'suspensions.record_id', '=', 'records.record_id')
            ->where('records.student_id', $student->student_id)
            ->orderByDesc('suspensions.created_at')
            ->first();
    }

    #[Computed]
    public function violation_list()
    {
        return Violations::with(['violation_descriptions'])
            ->whereIn('violation_id', [1, 3, 5, 6, 21, 17, 16, 12, 9,27])
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
                'remaining_days' => 3,
            ]);
        }
    }

    public function record_suspension(int $record_id)
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


        if (in_array($this->vio_violation_id, $set_a)) {

            Suspensions::create([
                'record_id' => $record_id,
                'num_of_days_suspended' => $record === 1 ? 3 : 4,
                'remaining_days' => $record === 1 ? 3 : 4,
            ]);

        } elseif (in_array($this->vio_violation_id, $set_b)) {

            Suspensions::create([
                'record_id' => $record_id,
                'num_of_days_suspended' => $record === 1 ? 5 : 7,
                'remaining_days' => $record === 1 ? 5 : 7,
            ]);

        } elseif (in_array($this->vio_violation_id, $set_c)) {

            Suspensions::create([
                'record_id' => $record_id,
                'num_of_days_suspended' => $record === 1 ? 6 : 8,
                'remaining_days' => $record === 1 ? 6 : 8,
            ]);

        } elseif (in_array($this->vio_violation_id, $set_e)) {

            Suspensions::create([
                'record_id' => $record_id,
                'num_of_days_suspended' => $record === 1 ? 9 : 12,
                'remaining_days' => $record === 1 ? 9 : 12,
            ]);
        }
    }

    public function confirm_violation()
    {
        $this->validate([
            'vio_sr_code'      => 'required',
            'vio_violation_id' => 'required|integer|in:1,3,5,6,21,17,16,12,9,27|exists:violations,violation_id',
        ], [
            'vio_sr_code.required'      => 'SR-Code is required.',
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
        if (!$this->show_confirm) {
            return;
        }
        $this->confirm_violation();
        $student = Students::where('sr_code', $this->vio_sr_code)
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
            'student_id'   => $student->student_id,
            'violation_id' => (int) $this->vio_violation_id,
        ]);

        $this->consecutive_minor($newRecord->record_id);
        $this->record_suspension($newRecord->record_id);

        $emailFailed = false;
        try {
            $violation = $newRecord->violations;
            Mail::to(trim($student->sr_code).'@g.batstate-u.edu.ph')->queue(new ViolationRecorded(
                studentName: $student->student_name,
                srCode: $student->sr_code,
                violationName: $violation->violation_name,
                severity: $violation->violation_weight,
                recordId: $newRecord->record_id,
                recordedAt: $newRecord->created_at->format('F j, Y g:i A T'),
            ));
        } catch (\Throwable $exception) {
            report($exception);
            $emailFailed = true;
        }

        $this->show_confirm = false;

        $this->reset([
            'vio_sr_code',
            'vio_violation_id',
            'vio_guard',
            'vio_notes',
            'vio_location'
        ]);

        $this->reset_student();
        unset($this->students, $this->violations, $this->suspensions, $this->suspension, $this->isSuspended);
        $this->dispatch('violation-recorded');
        if ($emailFailed) {
            $this->dispatch('show-toast', message: 'Violation saved, but the email notification could not be queued. Please contact the administrator.', type: 'error');
        }
    }

    public function cancel_confirm()
    {
        $this->show_confirm = false;
    }

    public function show_suspension() {}

    #[On('success_entry')]
    public function success_entry(?string $value): void
    {
        if (!$value) {
            return;
        }

        $this->sr_code = $value;
        $this->scanned = $value;

        if ($this->students && !$this->isSuspended) {
            $this->open();
        }
    }

    #[Computed]
    public function daysLeft()
    {
        if (!$this->suspension) {
            return 0;
        }

        $end = \Carbon\Carbon::parse($this->suspension->created_at)
            ->addDays($this->suspension->num_of_days_suspended);

        return max(
            0,
            now()->startOfDay()->diffInDays(
                $end->startOfDay(),
                false
            )
        );
    }

    #[Computed]
    public function isSuspended()
    {
        if (!$this->suspension) {
            return false;
        }

        $end = \Carbon\Carbon::parse($this->suspension->created_at)
            ->addDays($this->suspension->num_of_days_suspended);

        return now()->lt($end);
    }

    public function open()
    {
        try {

            GPIOService::move(18, 180);

            $this->angle = 180;

            $this->dispatch(
                'show-toast',
                message: 'Gate opened successfully.',
                type: 'success'
            );

        } catch (\Exception $e) {

            $this->dispatch(
                'show-toast',
                message: 'Failed to connect to Raspberry Pi.',
                type: 'error'
            );
        }
    }
};
?>

<div class="kiosk" x-data="{
    digits: '', busy: false, finishing: false, notice: '', noticeType: 'success',
    get code() { return this.digits.length > 2 ? this.digits.slice(0, 2) + '-' + this.digits.slice(2) : this.digits },
    async lookup() { if (this.digits.length !== 7 || this.busy) return; this.busy = true; try { await $wire.lookup_student(this.code) } finally { this.busy = false } },
    async clear() { this.digits = ''; this.busy = true; try { await $wire.reset_student() } finally { this.busy = false } },
    async finishRecording() {
        this.busy = true;
        this.finishing = true;
        await new Promise(resolve => setTimeout(resolve, 1000));
        this.digits = '';
        this.finishing = false;
        this.busy = false;
        this.noticeType = 'success';
        this.notice = 'Violation recorded successfully.';
        this.$nextTick(() => this.$refs.srInput.focus());
        setTimeout(() => this.notice = '', 5000);
    }
}" x-on:violation-recorded="finishRecording()" x-on:show-toast.window="notice = $event.detail.message; noticeType = $event.detail.type; setTimeout(() => notice = '', 5000)">
    <style>
        .kiosk { --ink:#20242c; --muted:#737780; --red:#b52835; --line:#e6e7eb; background:#f5f5f7; color:var(--ink); min-height:calc(100vh - 82px); padding:32px clamp(16px,3vw,48px); }
        .kiosk * { box-sizing:border-box; }
        .kiosk [x-cloak] { display:none!important; }
        .kiosk .eyebrow { font-size:11px; font-weight:800; letter-spacing:1.8px; text-transform:uppercase; color:var(--red); }
        .kiosk h1 { font-size:clamp(26px,3vw,36px); font-weight:750; letter-spacing:-1.2px; margin:6px 0; }
        .kiosk h2 { font-size:20px; font-weight:750; margin:0; letter-spacing:-.4px; }
        .kiosk p { color:var(--muted); }
        .kiosk .topbar { display:flex; justify-content:space-between; gap:20px; align-items:center; margin-bottom:26px; }
        .kiosk .date { border:1px solid var(--line); background:white; border-radius:30px; padding:10px 16px; font-size:13px; }
        .kiosk .workspace { display:grid; grid-template-columns:minmax(300px,390px) minmax(0,1fr); gap:24px; max-width:1500px; margin:auto; }
        .kiosk .panel { background:white; border:1px solid var(--line); border-radius:22px; padding:26px; box-shadow:0 5px 20px #20242c03; }
        .kiosk .keypad-panel { align-self:start; }
        .kiosk .step { display:inline-grid; place-items:center; background:#fbecef; color:var(--red); border-radius:10px; width:32px; height:32px; font-size:13px; font-weight:800; margin-right:10px; }
        .kiosk .section-title { display:flex; align-items:center; margin-bottom:18px; }
        .kiosk .code-input { width:100%; border:2px solid var(--line); border-radius:14px; background:#fafafb; text-align:center; font-family:monospace; font-size:34px; font-weight:700; letter-spacing:4px; padding:18px 8px; color:var(--ink); margin:8px 0; }
        .kiosk .helper { font-size:12px; color:var(--muted); display:block; }
        .kiosk .keys { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin:24px 0 18px; }
        .kiosk button { touch-action:manipulation; transition:background .15s,transform .1s,border-color .15s; }
        .kiosk button:focus-visible,.kiosk input:focus-visible { outline:3px solid #dd8991; outline-offset:3px; }
        .kiosk .key { min-height:72px; border:1px solid var(--line); border-radius:13px; background:white; font-size:28px; font-weight:650; color:var(--ink); box-shadow:0 3px 0 #ececf0; }
        .kiosk .key:hover { background:#fbecef; border-color:#dfadb2; }
        .kiosk .key:active { transform:translateY(2px); box-shadow:none; }
        .kiosk .key.utility { font-size:14px; background:#f5f5f7; color:#656973; }
        .kiosk .action { border:0; border-radius:12px; padding:15px 20px; min-height:52px; font-weight:700; font-size:14px; }
        .kiosk .primary { background:var(--red); color:white; }
        .kiosk .primary:hover { background:#94202b; }
        .kiosk .secondary { background:#f0f0f3; color:var(--ink); }
        .kiosk button:disabled { opacity:.45; cursor:not-allowed; }
        .kiosk .right-column { display:flex; flex-direction:column; gap:20px; min-width:0; }
        .kiosk .empty { text-align:center; padding:26px 16px; }
        .kiosk .empty-symbol { width:64px; height:64px; border-radius:20px; display:grid; place-items:center; margin:0 auto 16px; background:#f5f5f7; color:#969aa3; font-size:26px; }
        .kiosk .student { display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
        .kiosk .avatar { width:60px; height:60px; border-radius:18px; background:#fbecef; color:var(--red); display:grid; place-items:center; font-size:22px; font-weight:750; }
        .kiosk .pill { display:inline-block; border-radius:20px; padding:7px 12px; font-size:12px; font-weight:700; background:#e9f5ed; color:#267344; }
        .kiosk .pill.danger { color:#ad2632; background:#fbecef; }
        .kiosk .stats { display:flex; flex-wrap:wrap; gap:24px; border-top:1px solid var(--line); margin-top:20px; padding-top:16px; font-size:13px; color:var(--muted); }
        .kiosk .stats strong { font-size:20px; color:var(--ink); margin-right:5px; }
        .kiosk .violation-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
        .kiosk .violation { display:flex; align-items:center; gap:12px; text-align:left; padding:16px; min-height:74px; border:1px solid var(--line); border-radius:12px; background:white; color:var(--ink); font-size:14px; font-weight:600; }
        .kiosk .violation.selected { border-color:var(--red); background:#fff4f5; color:var(--red); box-shadow:0 0 0 1px var(--red); }
        .kiosk .choice { flex-shrink:0; width:22px; height:22px; border:1px solid #d6d8de; border-radius:50%; display:grid; place-items:center; }
        .kiosk .selected .choice { color:white; background:var(--red); border-color:var(--red); }
        .kiosk .footer { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-top:22px; padding-top:20px; border-top:1px solid var(--line); }
        .kiosk .error { color:#b52835; font-size:13px; margin-top:8px; }
        .kiosk .history { margin-top:20px; border-top:1px solid var(--line); padding-top:16px; }
        .kiosk summary { cursor:pointer; font-weight:650; font-size:14px; padding:6px 0; }
        .kiosk .history-row { display:flex; justify-content:space-between; gap:16px; padding:12px 0; border-bottom:1px solid var(--line); font-size:13px; }
        .kiosk .modal-shade { position:fixed; inset:0; background:#15192399; z-index:9999; display:grid; place-items:center; padding:20px; }
        .kiosk .confirm { width:100%; max-width:460px; box-shadow:0 24px 80px #0003; }
        .kiosk .notice { position:fixed; bottom:24px; right:24px; max-width:calc(100vw - 48px); background:#235b3b; color:white; padding:18px 24px; border-radius:14px; z-index:10000; box-shadow:0 8px 30px #0002; }
        .kiosk .save-progress { height:8px; overflow:hidden; border-radius:8px; background:#f0e1e3; }
        .kiosk .save-progress::after { content:''; display:block; height:100%; background:var(--red); transform-origin:left; animation:kiosk-save-progress 1s linear forwards; }
        @keyframes kiosk-save-progress { from { transform:scaleX(0); } to { transform:scaleX(1); } }
        @media(prefers-reduced-motion:reduce) { .kiosk .save-progress::after { animation:none; } }
        .kiosk .panel, .kiosk fieldset { min-width:0; }
        .kiosk .student > .flex-grow-1 { min-width:0; flex-basis:180px; }
        .kiosk .student h2, .kiosk .violation, .kiosk .history-row { overflow-wrap:anywhere; }
        .kiosk .avatar, .kiosk .step { flex-shrink:0; }
        .kiosk summary { min-height:48px; display:flex; align-items:center; gap:10px; }
        .kiosk summary::before { content:'+'; font-size:22px; }
        .kiosk details[open] summary::before { content:'−'; }
        .kiosk .confirm { max-height:calc(100dvh - 40px); overflow-y:auto; overscroll-behavior:contain; }
        @media(max-width:1199px) {
            .kiosk { padding:24px; padding-bottom:max(24px,env(safe-area-inset-bottom)); }
            .kiosk .workspace { grid-template-columns:minmax(300px,340px) minmax(0,1fr); gap:20px; }
            .kiosk .panel { padding:22px; }
            .kiosk .topbar { align-items:flex-start; gap:16px; }
            .kiosk .date { white-space:nowrap; }
            .kiosk .helper, .kiosk .error { font-size:14px; line-height:1.5; }
            .kiosk .action { min-height:56px; font-size:16px; }
            .kiosk .key { min-height:76px; }
            .kiosk .key.utility { font-size:16px; }
            .kiosk .violation { font-size:16px; min-height:80px; }
            .kiosk .violation-grid { grid-template-columns:1fr; gap:12px; }
            .kiosk .footer { flex-direction:column; align-items:stretch; }
        }
        /* Portrait tablets: give each step the full width instead of squeezing two columns. */
        @media(max-width:991px) {
            .kiosk .workspace { grid-template-columns:minmax(0,1fr); max-width:760px; }
            .kiosk .topbar { max-width:760px; margin-inline:auto; }
            .kiosk .date { display:none; }
            .kiosk .keypad-panel form { max-width:480px; margin-inline:auto; }
            .kiosk .keys { gap:12px; margin-top:20px; }
            .kiosk .key { min-height:80px; font-size:30px; }
            .kiosk .violation-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        }
        @media(max-width:575px) {
            .kiosk { padding:20px 14px; padding-bottom:max(20px,env(safe-area-inset-bottom)); }
            .kiosk .panel { padding:18px; border-radius:18px; }
            .kiosk .violation-grid { grid-template-columns:1fr; }
            .kiosk .key { min-height:72px; }
            .kiosk .history-row { flex-direction:column; gap:4px; }
            .kiosk .confirm .d-flex { flex-direction:column; }
        }
        /* Large tablets also need generous controls when their CSS width exceeds 1200px. */
        @media(pointer:coarse) {
            .kiosk .key { min-height:80px; }
            .kiosk .key.utility, .kiosk .action, .kiosk .violation { font-size:16px; }
            .kiosk .action { min-height:56px; }
            .kiosk .violation { min-height:80px; }
            .kiosk .helper { font-size:14px; }
        }
    </style>
    <header class="topbar">
        <div><div class="eyebrow">Campus safety / Student desk</div><h1>Student check-in</h1><p class="mb-0">Enter your SR code to get started.</p></div>
        <div class="date">{{ now()->format('l, F j, Y') }}</div>
    </header>
    <div class="workspace" x-bind:inert="finishing">
        <section class="panel keypad-panel" aria-labelledby="keypad-title">
            <div class="section-title"><span class="step">01</span><h2 id="keypad-title">Enter SR code</h2></div>
            <form x-on:submit.prevent="lookup()">
                <label for="sr-keypad" class="helper">Your student reference number</label>
                <input id="sr-keypad" x-ref="srInput" class="code-input" type="text" inputmode="numeric" autocomplete="off" placeholder="23-77394"
                    aria-describedby="code-help" x-bind:value="code" x-bind:disabled="busy"
                    x-on:input="digits = $event.target.value.replace(/\D/g, '').slice(0, 7); $event.target.value = code">
                <span id="code-help" class="helper text-center">Tap the numbers below or use your keyboard.</span>
                <div class="keys" role="group" aria-label="SR code number keypad">
                    @foreach(range(1, 9) as $digit)
                        <button type="button" class="key" x-bind:disabled="busy" x-on:click="if (digits.length < 7) digits += '{{ $digit }}'">{{ $digit }}</button>
                    @endforeach
                    <button type="button" class="key utility" x-bind:disabled="busy" x-on:click="clear()">Clear</button>
                    <button type="button" class="key" x-bind:disabled="busy" x-on:click="if (digits.length < 7) digits += '0'">0</button>
                    <button type="button" class="key utility" aria-label="Delete last digit" x-bind:disabled="busy" x-on:click="digits = digits.slice(0, -1)">&#9003; Delete</button>
                </div>
                <button type="submit" class="action primary w-100" x-bind:disabled="digits.length !== 7 || busy">
                    <span x-show="!busy">Find student &rarr;</span><span x-show="busy" x-cloak>Checking...</span>
                </button>
            </form>
            @error('sr_code') <div class="error" role="alert">{{ $message }}</div> @enderror
            <p class="helper text-center mt-3 mb-0">7 digits. The dash is added automatically.</p>
        </section>
        <div class="right-column">
            <section class="panel" aria-labelledby="student-title" aria-live="polite">
                <div class="section-title"><span class="step">02</span><h2 id="student-title">Student details</h2></div>
                @if($this->students)
                    <div class="student">
                        <div class="avatar" aria-hidden="true">{{ mb_substr($this->students->student_name, 0, 1) }}</div>
                        <div class="flex-grow-1"><h2>{{ $this->students->student_name }}</h2><p class="mb-0 mt-1">{{ $sr_code }} &middot; {{ $this->students->programs->program ?? 'No program listed' }}</p></div>
                        <span class="pill {{ $this->isSuspended ? 'danger' : '' }}">{{ $this->isSuspended ? 'Suspended' : 'Entry allowed' }}</span>
                    </div>
                    @if($this->isSuspended)<p class="error mb-0 mt-3">Active suspension &middot; {{ $this->daysLeft }} day(s) remaining.</p>@endif
                    <div class="stats"><span><strong>{{ $this->violations->count() }}</strong> violations</span><span><strong>{{ $this->suspensions->count() }}</strong> suspensions</span></div>
                    <details class="history">
                        <summary>View violation history</summary>
                        @forelse($this->violations as $record)
                            <div class="history-row"><span>{{ $record->violations->violation_name ?? 'Violation' }}</span><span>{{ $record->created_at?->format('M d, Y') }}</span></div>
                        @empty
                            <p class="helper mt-3 mb-0">No violations recorded for this student.</p>
                        @endforelse
                    </details>
                @else
                    <div class="empty"><div class="empty-symbol" aria-hidden="true">&#9678;</div><h2>Ready when you are</h2><p class="mt-2 mb-0">Enter your SR code, then tap Find student.<br>Your details will appear here.</p></div>
                @endif
            </section>
            <section class="panel" aria-labelledby="violation-title">
                <div class="section-title"><span class="step">03</span><h2 id="violation-title">Record a violation</h2></div>
                <p style="font-size:14px">Select the applicable violation, then review before saving.</p>
                <div x-show="$wire.sr_code && code !== $wire.sr_code" x-cloak class="error mb-3">The code has changed. Tap Find student to update the selected student.</div>
                <fieldset x-bind:disabled="busy || code !== $wire.sr_code || !$wire.vio_sr_code || $wire.show_confirm">
                    <legend class="visually-hidden">Choose a violation</legend>
                    <div class="violation-grid">
                        @foreach($this->violation_list as $violation)
                            <button type="button" wire:key="violation-{{ $violation->violation_id }}"
                                class="violation {{ (string) $vio_violation_id === (string) $violation->violation_id ? 'selected' : '' }}"
                                wire:click="$set('vio_violation_id', '{{ $violation->violation_id }}')"
                                aria-pressed="{{ (string) $vio_violation_id === (string) $violation->violation_id ? 'true' : 'false' }}">
                                <span class="choice" aria-hidden="true">{{ (string) $vio_violation_id === (string) $violation->violation_id ? '✓' : '' }}</span>
                                <span>{{ $violation->violation_name }}</span>
                            </button>
                        @endforeach
                    </div>
                </fieldset>
                @error('vio_violation_id')<div class="error" role="alert">{{ $message }}</div>@enderror
                @error('vio_sr_code')<div class="error" role="alert">{{ $message }}</div>@enderror
                @php($selected = $this->violation_list->firstWhere('violation_id', $vio_violation_id))
                @if($selected?->violation_descriptions->first())
                    <p class="helper mt-3 mb-0">{{ $selected->violation_descriptions->first()->description }}</p>
                @endif
                <div class="footer">
                    <span class="helper">{{ $this->students ? 'Check the student details before continuing.' : 'Find a student to enable violation recording.' }}</span>
                    <button type="button" class="action primary" wire:click="confirm_violation" wire:loading.attr="disabled"
                        x-bind:disabled="busy || code !== $wire.sr_code || !$wire.vio_sr_code || !$wire.vio_violation_id">Review violation &rarr;</button>
                </div>
            </section>
        </div>
    </div>
    @if($show_confirm)
        <div class="modal-shade" x-on:keydown.escape.window="$wire.cancel_confirm()">
            <section class="panel confirm" role="dialog" aria-modal="true" aria-labelledby="confirm-title" x-data x-init="$nextTick(() => $refs.cancel.focus())"
                x-on:keydown.tab.prevent="$event.target === $refs.cancel ? $refs.save.focus() : $refs.cancel.focus()">
                <div class="eyebrow mb-2">Review details</div><h2 id="confirm-title">Confirm this violation</h2>
                <p class="mt-3">Please verify the student and violation before recording.</p>
                <div class="p-3 rounded-3" style="background:#f5f5f7">
                    <span class="helper">Student</span><strong>{{ Students::where('sr_code', $vio_sr_code)->first()?->student_name }}</strong>
                    <div class="helper mt-1">{{ $vio_sr_code }}</div>
                    <span class="helper mt-3">Violation</span><strong>{{ $selected?->violation_name }}</strong>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="button" x-ref="cancel" class="action secondary" wire:click="cancel_confirm" wire:loading.attr="disabled">Go back</button>
                    <button type="button" x-ref="save" class="action primary flex-grow-1" wire:click="record_violation" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="record_violation">Confirm &amp; record</span>
                        <span wire:loading wire:target="record_violation">Saving...</span>
                    </button>
                </div>
            </section>
        </div>
    @endif
    <template x-if="finishing">
        <div class="modal-shade">
            <section class="panel confirm" role="status" aria-live="polite" aria-label="Preparing for the next student">
                <h2>Violation recorded</h2>
                <p class="mt-2">Preparing for the next student...</p>
                <div class="save-progress" aria-hidden="true"></div>
            </section>
        </div>
    </template>
    <div class="notice" x-cloak x-show="notice" x-transition x-text="notice" x-bind:style="noticeType === 'error' ? 'background:#a62532' : ''" role="status" aria-live="polite"></div>
</div>
