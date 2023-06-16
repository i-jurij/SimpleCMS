<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Order;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function index($content, $page_data, $path_array)
    {
        $data = [];
        $thisdata = [];
        $data['service_page'] = Page::where('publish', 'yes')
            ->where('service_page', 'yes')
            ->select('id', 'title', 'img')
            ->get()
            ->toArray();
        foreach ($data['service_page'] as $value) {
            $thisdata[$value['title']] = $value['img'];
        }

        $data['page_cats'] = ServiceCategory::select('id', 'page_id', 'name')
            ->get()
            ->toArray();
        $data['page_cats_serv'] = Service::whereNotNull('category_id')
            ->select('id', 'page_id', 'category_id', 'name', 'price', 'duration')
            ->get()
            ->toArray();
        $data['page_serv'] = Service::whereNull('category_id')
        ->select('id', 'page_id', 'category_id', 'name', 'price', 'duration')
        ->get()
        ->toArray();

        foreach ($data['service_page'] as $page) {
            foreach ($data['page_cats'] as $cat) {
                if ($cat['page_id'] === $page['id']) {
                    foreach ($data['page_cats_serv'] as $cat_serv) {
                        if ($cat_serv['category_id'] === $cat['id']) {
                            $thisdata['serv'][$page['title']][$cat['name']][$cat_serv['name']] = $cat_serv['price'].'-'.$cat_serv['duration'].'-'.$cat_serv['id'];
                        }
                    }
                }
            }
            foreach ($data['page_serv'] as $serv) {
                if ($serv['page_id'] === $page['id']) {
                    $thisdata['serv'][$page['title']]['page_serv'][$serv['name']] = $serv['price'].'-'.$serv['duration'].'-'.$serv['id'];
                }
            }
        }

        $thisdata['masters'] = Master::whereNull('data_uvoln')->get()->toArray();

        return view('client_manikur.client_pages.signup', ['page_data' => $page_data, 'content' => $content, 'data' => $thisdata]);
    }

    public function appoint_phone(Request $request)
    {
        if (!empty($request->master) and !empty($request->serv) and !empty($request->time)) {
            $master_id = my_sanitize_number($request->master);
            $time = (int) $request->time / 1000;
            list($duration, $serv_id) = explode('plus', $request->serv);

            // $start_dt = \DateTime::createFromFormat('d-m-Y H:i:s', '16-06-2023 14:30');
            $start_dt = Carbon::createFromTimestamp($time)->toDateTimeString();
            $order = Order::where('master_id', $master_id)
                ->where('start_dt', '<=', $start_dt)
                ->where('end_dt', '>', $start_dt)
                ->get()
                ->toArray();

            // check if start_dt not = or between srart_dt and end_dt for each item of $order collection

            if (!empty($order)) { // если таблица записей к мастеру пуста - выводим форму
                $res = ['res' => false];
            } else {
                $res = ['res' => true];
            }
        } else {
            $res = ['res' => 'empty'];
        }

        return json_encode($res);
    }
}
