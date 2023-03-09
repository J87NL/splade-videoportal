<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function __invoke(Request $request, string $command)
    {
        abort_if(! in_array($command, [
            'cache:clear',
            'config:clear',
            'migrate',
            'optimize:clear',
            'queue:retry all',
            'route:clear',
            'splade:cleanup-uploads',
            'sportivity:update-customer',
            'view:clear',
        ]), 403, 'Command not accepted');

        $params = $request->input();
        if ($command == 'migrate') {
            $params = ['--force' => true];
        }

        Artisan::call($command, $params);

        return view('admin.artisan.index', [
            'output' => nl2br(Artisan::output()),
        ]);
    }
}
