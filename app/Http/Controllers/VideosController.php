<?php

namespace App\Http\Controllers;

use App\Models\Dance;
use App\Models\Level;
use App\Models\Video;

class VideosController extends Controller
{
    public function index()
    {
        $dances = Dance::with([
            'videos' => function ($query) {
                $query->whereNotNull('path')->orWhereNotNull('url');
            },
            'videos.levels' => function ($query) {
                $query->select('id');
            },
            'videos.media',
        ])->get();

        return view('videos.index', [
            'dances' => $dances,
        ]);
    }

    public function indexWithLevels()
    {
        $dances = Dance::with([
            'videos' => function ($query) {
                $query->whereNotNull('path')->orWhereNotNull('url');
            },
            'videos.levels' => function ($query) {
                $query->select('id');
            },
        ])->get();

        return view('videos.index-with-levels', [
            'dances' => $dances,
            'levels' => Level::all(),
        ]);
    }

    public function show(Video $video)
    {
        $video->load(['dance', 'levels']);

        return view('videos.show', [
            'video' => $video,
        ]);
    }

    public function file(Video $video)
    {
        $path = storage_path('app/').$video->path;

        abort_if(! file_exists($path), 404);

        return response()->file($path);
    }
}
