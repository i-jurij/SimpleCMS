<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Master;
use Illuminate\Http\Request;

class SheduleMasterController extends Controller
{
    use \App\Traits\GetCalendarSettings;

    /**
     * Display a listing of the resource.
     */
    public function index(Master $masters)
    {
        $m['masters'] = $masters->whereNull('data_uvoln')->get()->toArray();

        return view('admin_manikur.moder_pages.shedule_masters', ['data' => $m]);
    }

    public function edit(Request $request)
    {
        $data = [];

        $data = $this->getCalSet();

        $data['master_freedaystimes'] = [];

        return view('admin_manikur.moder_pages.shedule_masters_edit', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $res['res'] = 'stored';

        return view('admin_manikur.moder_pages.shedule_masters', ['data' => $res]);
    }
}
