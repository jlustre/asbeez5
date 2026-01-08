<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    /**
     * Update the authenticated user's profile photo and persist `profile_photo_path`.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'profile_photo' => ['required', 'image', 'max:2048'], // max 2MB
        ]);

        // Delete old photo if present
        if (! empty($user->profile_photo_path) && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new photo on public disk under profile-photos
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        // Persist path on users.profile_photo_path
        $user->forceFill(['profile_photo_path' => $path])->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'profile-photo-updated',
                'url' => Storage::disk('public')->url($path),
            ]);
        }

        return back()->with('status', 'profile-photo-updated');
    }
}
