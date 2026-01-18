<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telp' => ['required', 'numeric', 'unique:users'],

            'npwp' => ['nullable', 'numeric'],
            'no_identitas' => ['required', 'numeric', 'unique:users'],
            'pekerjaan' => ['required', 'string'],
            'pendidikan' => ['required', 'string'],
            'alamat' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create($validated);

            event(new Registered($user));

            Auth::login($user);

            DB::commit();

            return redirect()->route('dashboard-pelayanan');
        } catch (Exception $error) {
            return redirect()->route('login')->with('error', 'Registrasi gagal. ' . $error->getMessage());
        }
    }
}
