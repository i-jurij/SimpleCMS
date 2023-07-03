<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GetCalendarSettings
{
    public function getCalSet()
    {
        // get data from orgworktimeset orgweekends holidays
        $sets = DB::table('orgworktimesets')->find(1);
        foreach ($sets as $key => $value) {
            $data[$key] = $value;
        }
        $data['worktime'] = [$data['work_start'], $data['work_end']];
        $data['lunch'] = [$data['lunch_time'], $data['lunch_duration']];
        unset($data['updated_at'], $data['created_at'], $data['id'], $data['work_start'], $data['work_end'], $data['lunch_time'], $data['lunch_duration']);

        $we = DB::table('orgweekends')->get();
        foreach ($we as $val) {
            $data['orgweekends'][$val->name_of_day_of_week] = $val->time;
        }

        $hol = DB::table('holidays')->get();
        foreach ($hol as $va) {
            $data['holidays'][] = $va->date;
        }

        return $data;
    }

    protected function get_restdaytimes($id)
    {
        $data = [];
        if (!empty($id)) {
            $sql = DB::table('restdaytimes')->where('master_id', $id)->get();
            foreach ($sql as $value) {
                if (!empty($value->time)) {
                    $data[$value->date][] = $value->time;
                } else {
                    $data[$value->date] = [];
                }
            }
        }
        // clear restdaytimes older then two year
        $two_year_ago = Carbon::today()->subYears(2)->toDateString();
        $clear = DB::table('restdaytimes')->where('master_id', $id)->where('date', '<', $two_year_ago)->delete();

        return $data;
    }
}
