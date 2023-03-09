<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;
use App\Models\Dance;
use App\Models\Level;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $dances = Dance::select('id', 'slug', 'title')->get();

        return view('admin.videos.index', [
            'dances' => $dances,
            'videos' => SpladeTable::for(Video::withCount('views'))
                ->defaultSort('position')
                ->withGlobalSearch(columns: ['title'])
                ->selectFilter(key: 'dance_id', options: $dances->pluck('title', 'id')->toArray(), label: __('Dance'))
                ->column(key: 'position', label: __('Order'), canBeHidden: false, sortable: true)
                ->column(key: 'title', label: __('Name'), canBeHidden: false, sortable: true)
                ->column('dance.title', label: __('Dance'), canBeHidden: true, hidden: ! empty($request->input('filter.dance_id')))
                ->column('levels.title', label: __('Level'), canBeHidden: true)
                ->column('views_count', label: __('Viewcount'), canBeHidden: true, sortable: true)
                ->column('action', label: __('Options'), canBeHidden: false)
                ->rowModal(fn (Video $video) => route('admin.videos.edit', ['video' => $video]))
                ->paginate(25),
        ]);
    }

    public function create(): View
    {
        $dances = Dance::select('id', 'title')->pluck('title', 'id');
        $levels = Level::select('id', 'title')->pluck('title', 'id');

        return view('admin.videos.create', [
            'dances' => $dances,
            'levels' => $levels,
        ]);
    }

    public function store(VideoRequest $request)
    {
        $video = Video::create($request->safe()->except(['videoPath', 'image']));

        $video->levels()->sync($request->levels);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $video->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($request->hasFile('videoPath') && $request->file('videoPath')->isValid()) {
            $video->path = $request->file('videoPath')->store('videos');
            $video->save();
        }

        Toast::title(__('Saved successfully'))
            ->message(__("New :type ':title' successfully added", ['type' => __('video'), 'title' => $video->title]));

        return redirect()->route('admin.videos.index');
    }

    public function edit(Video $video)
    {
        $video->loadCount('views');

        $dances = Dance::select('id', 'title')->pluck('title', 'id');
        $levels = Level::select('id', 'title')->pluck('title', 'id');

        return view('admin.videos.edit', [
            'video' => $video,
            'dances' => $dances,
            'levels' => $levels,
        ]);
    }

    public function update(VideoRequest $request, Video $video)
    {
        $video->update($request->safe()->except(['videoPath', 'image']));

        $video->levels()->sync($request->levels);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $video->addMediaFromRequest('image')->toMediaCollection('images');
        }

        if ($request->hasFile('videoPath') && $request->file('videoPath')->isValid()) {
            $oldVideoPath = $video->path ?? null;

            $video->path = $request->file('videoPath')->store('videos');
            $video->save();

            if (! empty($oldVideoPath)) {
                Storage::delete($oldVideoPath);
            }
        }

        Toast::title(__('Saved successfully'))
            ->message(__(":Type ':title' has been updated", ['type' => __('video'), 'title' => $video->title]));

        return redirect()->route('admin.videos.index', ['filter[dance_id]' => $video->dance_id]);
    }

    public function destroy(Video $video)
    {
        $title = $video->title;

        $video->delete();

        Toast::title(__('Removed successfully'))
            ->message(__(":Type ':title' has been removed", ['type' => __('video'), 'title' => $title]));

        return redirect()->route('admin.videos.index');
    }
}
