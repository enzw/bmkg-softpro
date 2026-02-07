<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Ensure user is authenticated and verified
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Security: Ensure user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthorized profile access attempt');
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Security: Triple-check user authorization
        if (!Auth::check()) {
            Log::warning('Update profile attempt without authentication');
            return Redirect::route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        if (Auth::id() !== $request->user()->id) {
            Log::warning('Unauthorized profile update attempt - User ID mismatch. Attempted by: ' . Auth::id());
            return Redirect::route('profile.edit')->with('error', 'Anda tidak memiliki izin untuk mengubah profil ini.');
        }

        // Verify request is not tampered
        if (!$request->authorize()) {
            Log::warning('Profile update request authorization failed for user: ' . Auth::id());
            return Redirect::route('profile.edit')->with('error', 'Permintaan tidak valid. Silakan coba lagi.');
        }

        $user = $request->user();
        $oldEmail = $user->email;
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            Log::info('User email changed from: ' . $oldEmail . ' to: ' . $user->email . ' by user: ' . Auth::id());
        }

        $user->save();

        Log::info('User profile successfully updated by user: ' . Auth::id());

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Security: Double-check user authorization before deletion
        if (!Auth::check() || Auth::id() !== $request->user()->id) {
            Log::warning('Unauthorized account deletion attempt by user: ' . Auth::id());
            return Redirect::route('profile.edit')->with('error', 'Anda tidak memiliki izin untuk menghapus akun ini.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $userId = $user->id;
        $userName = $user->name;

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::warning('User account deleted: ' . $userId . ' (' . $userName . ')');

        return Redirect::to('/')->with('status', 'account-deleted');
    }
}
