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
        $res = $this->getCalSet();
        $data = [
            'lehgth_cal' => $res['lehgth_cal'],
            'endtime' => $res['endtime'],
            'period' => $res['period'],
            'worktime' => $res['worktime'],
            'lunch' => $res['lunch'],
            'org_weekend' => $res['orgweekends'],
            'holiday' => $res['holidays'],
            'rest_day_time' => null,
            'exist_app_date_time_arr' => null,
            'serv_duration' => '',
        ];

        $data['master_freedaystimes'] = [];

        // return view('admin_manikur.moder_pages.shedule_masters_edit', ['data' => $data]);
        return response()->json($data);
    }

    public function calendarSettings()
    {
    }

    public function store(Request $request)
    {
        $res['res'] = 'stored';

        return view('admin_manikur.moder_pages.shedule_masters', ['data' => $res]);
    }
}
