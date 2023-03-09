<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DancetypeRequest;
use App\Models\Dancetype;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;

class DanceypesController extends Controller
{
    public function index()
    {
        return view('admin.dancetypes.index', [
            'dancetypes' => SpladeTable::for(Dancetype::class)
                ->defaultSort('position')
                ->withGlobalSearch(columns: ['title'])
                ->column(key: 'position', label: __('Order'), canBeHidden: false, sortable: true)
                ->column(key: 'title', label: __('Name'), canBeHidden: false, sortable: true)
                ->column(key: 'action', label: __('Options'), canBeHidden: false)
                ->rowModal(fn (Dancetype $dancetype) => route('admin.dancetypes.edit', ['dancetype' => $dancetype]))
                ->paginate(25),
        ]);
    }

    public function create(): View
    {
        return view('admin.dancetypes.create');
    }

    public function store(DancetypeRequest $request)
    {
        $dancetype = Dancetype::create($request->validated());

        Toast::title(__('Saved successfully'))
            ->message(__("New :type ':title' successfully added", ['type' => __('dancetype'), 'title' => $dancetype->title]));

        return redirect()->route('admin.dancetypes.index');
    }

    public function edit(Dancetype $dancetype)
    {
        return view('admin.dancetypes.edit', [
            'dancetype' => $dancetype,
        ]);
    }

    public function update(DancetypeRequest $request, Dancetype $dancetype)
    {
        $dancetype->update($request->validated());

        Toast::title(__('Saved successfully'))
            ->message(__(":Type ':title' has been updated", ['type' => __('dancetype'), 'title' => $dancetype->title]));

        return redirect()->route('admin.dancetypes.index');
    }

    public function destroy(Dancetype $dancetype)
    {
        $title = $dancetype->title;

        $dancetype->delete();

        Toast::title(__('Removed successfully'))
            ->message(__(":Type ':title' has been removed", ['type' => __('dancetype'), 'title' => $title]));

        return redirect()->route('admin.dancetypes.index');
    }
}
