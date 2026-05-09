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
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile information updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
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
                return back()->with('success', 'Avatar updated and resized to 200x200!');
            } else {
                // Fallback if GD is missing: just store normally
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->update(['avatar' => $path]);
                return back()->with('warning', 'Avatar updated, but could not be resized because GD extension is disabled. Please enable it in XAMPP.');
            }
        } catch (\Exception $e) {
            // Last resort fallback
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
            return back()->with('success', 'Avatar updated (without resizing due to a server error).');
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted.');
    }
}
