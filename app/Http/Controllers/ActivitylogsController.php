<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\Activitylog\Models\Activity;

class ActivitylogsController extends Controller
{
    public function __invoke(Request $request)
    {
//        dd(Activity::with('subject')->paginate()->toArray());
        return view('admin.activitylogs.index', [
            'activitylogs' => SpladeTable::for(Activity::with('subject')->orderByDesc('id'))
//                ->defaultSort('id', 'desc')
//                ->withGlobalSearch(columns: ['title'])
                ->column(key: 'event', label: __('Event'), canBeHidden: false, sortable: true)
                ->column(key: 'subject_type', label: __('Type'), canBeHidden: false, sortable: true)
                ->column(key: 'subject_name', label: __('Name'), canBeHidden: false, sortable: true)
                ->column(key: 'attributes', label: __('Attributes'), canBeHidden: false, sortable: false)
                ->column(key: 'created_at', label: __('Date'), canBeHidden: false, sortable: true)
                ->paginate(25),
        ]);
    }
}
