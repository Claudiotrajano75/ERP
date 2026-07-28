<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PdvDraft;
use Illuminate\Support\Facades\Auth;

class PdvDraftController extends Controller
{
    public function save(Request $request)
    {
        $user_id = Auth::id();
        $terminal_id = $request->input('terminal_id', 'default');

        $draft = PdvDraft::updateOrCreate(
            ['user_id' => $user_id, 'terminal_id' => $terminal_id],
            [
                'payload' => json_encode($request->all()),
                'ip' => $request->ip()
            ]
        );

        return response()->json(['success' => true]);
    }

    public function current(Request $request)
    {
        $user_id = Auth::id();
        $terminal_id = $request->query('terminal_id', 'default');

        $draft = PdvDraft::where('user_id', $user_id)
            ->where('terminal_id', $terminal_id)
            ->first();

        if ($draft) {
            return response()->json([
                'has_draft' => true,
                'draft' => json_decode($draft->payload, true)
            ]);
        }

        return response()->json(['has_draft' => false]);
    }

    public function clear(Request $request)
    {
        $user_id = Auth::id();
        $terminal_id = $request->input('terminal_id', 'default');
        PdvDraft::where('user_id', $user_id)
            ->where('terminal_id', $terminal_id)
            ->delete();

        return response()->json(['success' => true]);
    }
}
