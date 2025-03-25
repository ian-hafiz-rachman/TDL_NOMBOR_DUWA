<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'current_password' => ['required', 'current_password'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai'
        ]);

        try {
            $user = auth()->user();
            
            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return redirect()
                ->route('profile.edit')
                ->with('success', 'Profile berhasil diperbarui');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profile: ' . $e->getMessage());
        }
    }

    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required'
            ]);

            $user = auth()->user();

            // Decode base64 image
            $image = $request->file('avatar');
            
            // Generate unique filename
            $fileName = 'avatars/' . uniqid() . '.jpg';
            
            // Create directory if it doesn't exist
            $directory = storage_path('app/public/avatars');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Save new avatar
            Storage::disk('public')->put($fileName, file_get_contents($image));

            // Update user avatar in database
            $user->avatar = $fileName;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar berhasil diperbarui',
                'path' => Storage::url($fileName)
            ]);

        } catch (\Exception $e) {
            \Log::error('Avatar update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        \Log::info('Password update attempt for user: ' . Auth::id());
        
        try {
            // Validate request
            $validated = $request->validate([
                'old_password' => ['required'],
                'new_password' => ['required', 'min:8', 'confirmed'],
            ]);

            // Check if old password matches
            if (!Hash::check($request->old_password, Auth::user()->password)) {
                \Log::warning('Invalid old password provided for user: ' . Auth::id());
                return back()
                    ->withErrors(['old_password' => 'Password lama tidak sesuai'])
                    ->withInput();
            }

            // Update the password
            $user = Auth::user();
            $user->password = Hash::make($validated['new_password']);
            $user->save();

            \Log::info('Password successfully updated for user: ' . Auth::id());

            return redirect()
                ->route('profile.edit')
                ->with('success', 'Password berhasil diperbarui');
                
        } catch (\Exception $e) {
            \Log::error('Password update failed for user: ' . Auth::id() . '. Error: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui password. Silakan coba lagi.');
        }
    }
} 