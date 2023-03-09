<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LevelRequest;
use App\Models\Level;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;

class LevelController extends Controller
{
    public function index()
    {
        return view('admin.levels.index', [
            'levels' => SpladeTable::for(Level::class)
                ->defaultSort('position')
                ->withGlobalSearch(columns: ['title'])
                ->column(key: 'position', label: __('Order'), canBeHidden: false, sortable: true)
                ->column(key: 'title', label: __('Level'), canBeHidden: false, sortable: true)
                ->column(key: 'action', label: __('Options'), canBeHidden: false)
                ->rowModal(fn (Level $level) => route('admin.levels.edit', ['level' => $level]))
                ->paginate(25),
        ]);
    }

    public function create(): View
    {
        return view('admin.levels.create');
    }

    public function store(LevelRequest $request)
    {
        $level = Level::create($request->validated());

        Toast::title(__('Saved successfully'))
            ->message(__("New :type ':title' successfully added", ['type' => __('level'), 'title' => $level->title]));

        return redirect()->route('admin.levels.index');
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', [
            'level' => $level,
        ]);
    }

    public function update(LevelRequest $request, Level $level)
    {
        $level->update($request->validated());

        Toast::title(__('Saved successfully'))
            ->message(__(":Type ':title' has been updated", ['type' => __('level'), 'title' => $level->title]));

        return redirect()->route('admin.levels.index');
    }

    public function destroy(Level $level)
    {
        $title = $level->title;

        $level->delete();

        Toast::title(__('Removed successfully'))
            ->message(__(":Type ':title' has been removed", ['type' => __('level'), 'title' => $title]));

        return redirect()->route('admin.levels.index');
    }
}
