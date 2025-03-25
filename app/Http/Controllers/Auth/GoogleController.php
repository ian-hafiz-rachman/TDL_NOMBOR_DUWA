<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            \Log::error('Google redirect error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat menghubungkan ke Google.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            \Log::info('Google callback received for email: ' . $googleUser->email);
            
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Buat user baru jika belum ada
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)), // Menggunakan Str::random()
                    'email_verified_at' => now(), // Email sudah terverifikasi dari Google
                ]);
                \Log::info('New user created: ' . $user->email);
            } elseif (!$user->google_id) {
                // Update google_id hanya jika belum ada
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
                \Log::info('Updated google_id for user: ' . $user->email);
            }

            Auth::login($user);
            \Log::info('User logged in successfully: ' . $user->email);

            return redirect()->route('dashboard')->with('success', 'Berhasil masuk menggunakan akun Google.');

        } catch (Exception $e) {
            \Log::error('Google callback error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
} 