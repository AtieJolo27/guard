<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $t) { $t->id('department_id'); $t->string('department_name'); $t->timestamps(); });
            Schema::create('programs', function (Blueprint $t) { $t->id('program_id'); $t->foreignId('department_id')->constrained('departments', 'department_id')->cascadeOnDelete(); $t->string('program'); $t->timestamps(); });
            Schema::create('students', function (Blueprint $t) { $t->id('student_id'); $t->foreignId('program_id')->constrained('programs', 'program_id')->cascadeOnDelete(); $t->string('student_name'); $t->string('sr_code')->unique(); $t->string('gsuite')->unique(); $t->string('password'); $t->string('student_profile')->nullable(); $t->string('disciplinary_status')->default('active'); $t->string('year_level')->nullable(); $t->timestamps(); });
            Schema::create('guards', function (Blueprint $t) { $t->id('guard_id'); $t->string('name'); $t->string('email')->unique(); $t->string('password'); $t->timestamps(); });
            Schema::create('admins', function (Blueprint $t) { $t->id('admin_id'); $t->string('email')->unique(); $t->string('password'); $t->timestamps(); });
            Schema::create('violation_descriptions', function (Blueprint $t) { $t->id('description_id'); $t->text('description'); $t->timestamps(); });
            Schema::create('violations', function (Blueprint $t) { $t->id('violation_id'); $t->string('violation_name'); $t->string('violation_weight'); $t->foreignId('description_id')->nullable()->constrained('violation_descriptions', 'description_id')->nullOnDelete(); $t->timestamps(); });
            Schema::create('records', function (Blueprint $t) { $t->id('record_id'); $t->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete(); $t->foreignId('violation_id')->constrained('violations', 'violation_id')->cascadeOnDelete(); $t->timestamps(); });
        }

        if (! Schema::hasTable('violation_reports')) {
            Schema::create('violation_reports', function (Blueprint $t) {
                $t->id();
                $t->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete();
                $t->foreignId('guard_id')->constrained('guards', 'guard_id')->restrictOnDelete();
                $t->foreignId('violation_id')->constrained('violations', 'violation_id')->restrictOnDelete();
                $t->foreignId('record_id')->nullable()->unique()->constrained('records', 'record_id')->nullOnDelete();
                $t->string('classification');
                $t->dateTime('incident_at')->index();
                $t->string('location');
                $t->text('incident_statement');
                $t->string('status')->index();
                $t->text('investigation_notes')->nullable();
                $t->dateTime('meeting_at')->nullable();
                $t->text('decision_reason')->nullable();
                $t->unsignedBigInteger('reviewed_by')->nullable();
                $t->timestamp('reviewed_at')->nullable();
                $t->string('submission_token')->unique();
                $t->timestamps();
            });
        }
        if (! Schema::hasTable('report_actions')) {
            Schema::create('report_actions', function (Blueprint $t) {
                $t->id(); $t->foreignId('violation_report_id')->constrained()->cascadeOnDelete();
                $t->string('action'); $t->string('actor_type'); $t->unsignedBigInteger('actor_id')->nullable(); $t->text('notes')->nullable(); $t->timestamps();
            });
        }
        if (! Schema::hasTable('notification_deliveries')) {
            Schema::create('notification_deliveries', function (Blueprint $t) {
                $t->id(); $t->foreignId('violation_report_id')->constrained()->cascadeOnDelete();
                $t->string('recipient_type'); $t->string('recipient_email'); $t->string('notification_type'); $t->string('status'); $t->text('error_message')->nullable(); $t->timestamp('sent_at')->nullable(); $t->timestamps();
                $t->unique(['violation_report_id', 'recipient_type', 'notification_type'], 'notification_deliveries_recipient_unique');
            });
        }
    }
    public function down(): void { Schema::dropIfExists('notification_deliveries'); Schema::dropIfExists('report_actions'); Schema::dropIfExists('violation_reports'); }
};
