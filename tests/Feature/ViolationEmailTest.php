<?php

namespace Tests\Feature;

use App\Mail\ViolationRecorded;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class ViolationEmailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('sr_code');
            $table->string('student_name');
        });
        Schema::create('violations', function (Blueprint $table) {
            $table->id('violation_id');
            $table->string('violation_name');
            $table->string('violation_weight');
            $table->unsignedBigInteger('description_id')->nullable();
        });
        Schema::create('violation_descriptions', function (Blueprint $table) {
            $table->id('description_id');
        });
        Schema::create('records', function (Blueprint $table) {
            $table->id('record_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('violation_id');
            $table->timestamps();
        });
        Schema::create('suspensions', function (Blueprint $table) {
            $table->id('suspension_id');
            $table->unsignedBigInteger('record_id');
            $table->integer('num_of_days_suspended');
            $table->integer('remaining_days');
            $table->timestamps();
        });

        DB::table('students')->insert(['student_id' => 1, 'sr_code' => '22-12345', 'student_name' => 'Test Student']);
        DB::table('violations')->insert(['violation_id' => 1, 'violation_name' => 'Improper uniform', 'violation_weight' => 'MINOR']);
        Mail::fake();
    }

    public function test_confirmed_violation_queues_notice_to_student(): void
    {
        Livewire::test('dashboard')
            ->set('vio_sr_code', '22-12345')
            ->set('vio_violation_id', '1')
            ->call('confirm_violation')
            ->call('record_violation')
            ->assertHasNoErrors()
            ->assertDispatched('violation-recorded');

        $this->assertDatabaseHas('records', ['student_id' => 1, 'violation_id' => 1]);
        Mail::assertQueued(ViolationRecorded::class, function (ViolationRecorded $mail) {
            $mail->assertSeeInHtml('Test Student');
            $mail->assertSeeInHtml('Improper uniform');
            $mail->assertSeeInHtml('MINOR');

            return $mail->hasTo('22-12345@g.batstate-u.edu.ph') && $mail->recordId === 1;
        });
        Mail::assertQueuedCount(1);
    }

    public function test_unconfirmed_violation_does_not_send_email(): void
    {
        Livewire::test('dashboard')
            ->set('vio_sr_code', '22-12345')
            ->set('vio_violation_id', '1')
            ->call('record_violation');

        $this->assertDatabaseCount('records', 0);
        Mail::assertNothingQueued();
    }
}
