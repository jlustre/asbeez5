<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileBirthdateController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'birthdate' => ['nullable', 'date'],
        ]);

        $profile = $request->user()->profile;
        $profile->forceFill(['birthdate' => $data['birthdate'] ?? null])->save();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'birthdate-updated', 'birthdate' => $profile->birthdate]);
        }

        return back()->with('status', 'birthdate-updated');
    }
}
