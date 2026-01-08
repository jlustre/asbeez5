<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileBackgroundController extends Controller
{
    /**
     * Update the authenticated user's avatar background URL on the profile.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        $request->validate([
            'background_url' => ['nullable', 'url'],
            'background_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $url = $request->string('background_url')->toString();

        if ($request->hasFile('background_image')) {
            $path = $request->file('background_image')->store('profile-backgrounds', 'public');
            $url = Storage::disk('public')->url($path);
        }

        if (empty($url)) {
            return back()->with('status', 'no-background-provided');
        }

        $profile->forceFill(['background_url' => $url])->save();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'background-updated', 'url' => $url]);
        }

        return back()->with('status', 'background-updated');
    }
}
