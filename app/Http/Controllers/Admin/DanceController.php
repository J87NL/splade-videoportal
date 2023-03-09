<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DanceRequest;
use App\Models\Dance;
use App\Models\Dancetype;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;

class DanceController extends Controller
{
    public function index()
    {
        return view('admin.dances.index', [
            'dances' => SpladeTable::for(Dance::class)
                ->defaultSort('position')
                ->withGlobalSearch(columns: ['title'])
                ->column(key: 'position', label: __('Order'), canBeHidden: false, sortable: true)
                ->column(key: 'title', label: __('Name'), canBeHidden: false, sortable: true)
                ->column(key: 'dancetype.title', label: __('Type'), canBeHidden: false, sortable: true)
                ->column(key: 'action', label: __('Options'), canBeHidden: false)
                ->rowModal(fn (Dance $dance) => route('admin.dances.edit', ['dance' => $dance]))
                ->paginate(25),
        ]);
    }

    public function create(): View
    {
        $dancetypes = Dancetype::select('id', 'title')->pluck('title', 'id');

        return view('admin.dances.create', [
            'dancetypes' => $dancetypes,
        ]);
    }

    public function store(DanceRequest $request)
    {
        $dance = Dance::create($request->validated());

        Toast::title(__('Saved successfully'))
            ->message(__("New :type ':title' successfully added", ['type' => __('dance'), 'title' => $dance->title]));

        return redirect()->route('admin.dances.index');
    }

    public function edit(Dance $dance)
    {
        $dancetypes = Dancetype::select('id', 'title')->pluck('title', 'id');

        return view('admin.dances.edit', [
            'dance' => $dance,
            'dancetypes' => $dancetypes,
        ]);
    }

    public function update(DanceRequest $request, Dance $dance)
    {
        $dance->update($request->validated());

        Toast::title(__('Saved successfully'))
            ->message(__(":Type ':title' has been updated", ['type' => __('dance'), 'title' => $dance->title]));

        return redirect()->route('admin.dances.index');
    }

    public function destroy(Dance $dance)
    {
        $title = $dance->title;

        $dance->delete();

        Toast::title(__('Removed successfully'))
            ->message(__(":Type ':title' has been removed", ['type' => __('dance'), 'title' => $title]));

        return redirect()->route('admin.dances.index');
    }
}
