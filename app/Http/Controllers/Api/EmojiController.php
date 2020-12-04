<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class EmojiController extends Controller
{
    public function listForUser($id)
    {
        $user = User::findOrFail($id);
        $emojis = $user->emojis;

        return response()->json($emojis);
    }
}
