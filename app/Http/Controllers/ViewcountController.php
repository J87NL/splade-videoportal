<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class ViewcountController extends Controller
{
    public function store(Request $request, Video $video)
    {
        if ($request->user()->is_admin) {
            return response()->json(['success' => true, 'user' => 'is_admin']);
        }

        $video->views()->create([
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['success' => true]);
    }
}
