<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone' => ['nullable', 'string', 'max:20'],
            ]);

            $user->update($validated);
            return back()->with('success', 'Your profile identity has been updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Profile Update Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while updating your profile.');
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $request->user()->update([
                'password' => Hash::make($request->password),
            ]);

            return back()->with('success', 'Security settings updated. Your password is now secure.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Password Update Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update password. Please check your credentials.');
        }
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:5120'],
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        try {
            if (extension_loaded('gd')) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($request->file('avatar'));
                $image->cover(200, 200);
                $filename = 'avatars/' . $request->file('avatar')->hashName();
                Storage::disk('public')->put($filename, (string) $image->toJpeg());
                $user->update(['avatar' => $filename]);
                return back()->with('success', 'Your new avatar is live and optimized!');
            } else {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->update(['avatar' => $path]);
                return back()->with('success', 'Avatar updated successfully.');
            }
        } catch (\Exception $e) {
            \Log::error('Avatar Update Error: ' . $e->getMessage());
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
            return back()->with('success', 'Avatar updated (Standard mode).');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
                'confirmation' => ['required', 'string', 'in:DELETE MY ACCOUNT'],
            ]);

            $user = Auth::user();
            Auth::logout();
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Account and data permanently removed.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Account Destruction Error: ' . $e->getMessage());
            return back()->with('error', 'Critical error during account deletion. Please contact support.');
        }
    }
}
