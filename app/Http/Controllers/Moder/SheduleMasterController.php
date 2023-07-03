<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Master;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        $data = '';

        $validated = $request->validate([
            'master' => 'required|',
            'date.*' => 'numeric',
            'daytime.*' => 'numeric',
            'deldate.*' => 'numeric',
            'deltime.*' => 'numeric',
        ]);

        if (!empty($request->date)) {
            foreach ($request->date as $value) {
                $date = CarbonImmutable::createFromTimestamp($value / 1000)->toDateString();
                $insert_date[] = ['master_id' => $request->master, 'date' => $date];
            }
            $sql_insert_date = DB::table('restdaytimes')->insert($insert_date);
        }

        if (!empty($request->daytime)) {
            foreach ($request->daytime as $val) {
                $datetime = CarbonImmutable::createFromTimestamp($val / 1000);
                $insert_daytime[] = ['master_id' => $request->master, 'date' => $datetime->toDateString(), 'time' => $datetime->format('H:i')];
            }
            $sql_insert_daytime = DB::table('restdaytimes')->insert($insert_daytime);
        }

        if (!empty($request->deldate)) {
            foreach ($request->deldate as $va) {
                $deldate = CarbonImmutable::createFromTimestamp($va / 1000)->toDateString();
                $delete_date[] = ['master_id' => $request->master, 'date' => $deldate];
            }
            $sql_delete_date = DB::table('restdaytimes')->where($delete_date)->delete();
        }

        if (!empty($request->deltime)) {
            foreach ($request->deltime as $v) {
                $deldatetime = CarbonImmutable::createFromTimestamp($v / 1000);
                $delete_daytime[] = ['master_id' => $request->master, 'date' => $deldatetime->toDateString(), 'time' => $deldatetime->format('H:i')];
            }
            $sql_insert_daytime = DB::table('restdaytimes')->where($delete_daytime)->delete();
        }

        $data = 'SUCCESS! Data for schedule of master have been stored.';

        // return view('admin_manikur.moder_pages.shedule_masters', ['data' => $data]);
        return back()->with('data', $data);
    }
}
