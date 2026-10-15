<?php

namespace Tests\Feature;

use App\Models\Guards;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class GuardLoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('guards', function (Blueprint $table) {
            $table->id('guard_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function test_password_is_hashed_and_correct_password_logs_in(): void
    {
        $guard = new Guards;
        $guard->name = 'Test Guard';
        $guard->email = 'guard@example.com';
        $guard->password = 'test-password';
        $guard->save();

        $this->assertNotSame('test-password', $guard->fresh()->password);
        $this->assertTrue(Hash::check('test-password', $guard->fresh()->password));

        Livewire::test('auth.login')
            ->set('email', $guard->email)
            ->set('password', 'test-password')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));
        $this->assertSame($guard->guard_id, session('guard_id'));
    }

    public function test_wrong_password_and_legacy_plaintext_are_rejected(): void
    {
        DB::table('guards')->insert([
            'name' => 'Test Guard',
            'email' => 'guard@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        Livewire::test('auth.login')->set('email', 'guard@example.com')
            ->set('password', 'wrong-password')->call('login')->assertHasErrors('email');

        DB::table('guards')->update(['password' => 'legacy-password']);
        Livewire::test('auth.login')->set('email', 'guard@example.com')
            ->set('password', 'legacy-password')->call('login')->assertHasErrors('email');
        $this->assertFalse(session()->has('guard_id'));
    }
}
