<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $r)
    {
        $data = $r->validate(['email' => 'required|string|max:254', 'password' => 'required|string|max:1024']);
        $account = DB::table('admins')->where('email', $data['email'])->first();
        $valid = Hash::check($data['password'], $account->password ?? '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.');
        if (! $account || ! $valid) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        } $r->session()->regenerate();
        $r->session()->put(['svrms_role' => 'admin', 'svrms_id' => $account->admin_id, 'svrms_name' => $account->name ?? $account->email]);

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $r)
    {
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('login');
    }
}
