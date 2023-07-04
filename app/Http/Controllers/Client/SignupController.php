<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Master;
use App\Models\Order;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    use \App\Traits\GetCalendarSettings;
    use \App\Traits\GetRestDayTimes;
    use \App\Traits\GetAppointment;

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

        // $thisdata['masters'] = Master::whereNull('data_uvoln')->get()->toArray();

        return view('client_manikur.client_pages.signup', ['page_data' => $page_data, 'content' => $content, 'data' => $thisdata]);
    }

    public function appoint_masters(Request $request)
    {
        $res = [];
        if (!empty($request->serv_id)) {
            $masters = Service::find($request->serv_id)->masters;
            $res = [
                'masters' => $masters,
            ];
        }

        return json_encode($res);
    }

    protected function check_free_by_all_masters($start_dt)
    {
        // проверим, что будут свободные мастера
        // get count of order by approximately equal start time
        $num_of_order = Order::where('start_dt', '<=', $start_dt)
            ->where('end_dt', '>', $start_dt)
            ->count();
        // get count of masters
        $num_of_masters = Master::count();
        // если общее количество заказов на данное время меньше, чем количество мастеров
        if ($num_of_order < $num_of_masters) {
            return true;
        } else {
            return false;
        }
    }

    protected function check_free_by_master_id($master_id, $start_dt)
    {
        $order = Order::where('master_id', $master_id)
        ->where('start_dt', '<=', $start_dt)
        ->where('end_dt', '>', $start_dt)
        ->first();

        // check if start_dt not = or between srart_dt and end_dt for each item of $order collection
        if (!empty($order->id)) {
            $res = false;
        } else {
            // if this is the chosen time, the master is free
            $res = true;
        }

        return $res;
    }

    /**
     * @param int    $client   id
     * @param string $start_dt - service start time
     *
     * @return array or null
     */
    protected function check_client_other_signup(int $client_id, $start_dt)
    {
        $order_isset = Order::where('client_id', $client_id)
        ->where('start_dt', '<=', $start_dt)
        ->where('end_dt', '>', $start_dt)
        ->first();

        if (!empty($order_isset->id)) {
            $res = $order_isset->with('master')->with('service')->get()->toArray();

            $serv = Service::find($order_isset->service_id);
            $page = $serv->page->title;
            $cat = $serv->category->name;
            $serv_name = $serv->name;
            $serv_price = $serv->price;
            $res = [
                'order_id' => $order_isset->id,
                'time' => $order_isset->start_dt,
                'master' => '',
                'service' => $page.', '.$cat.', '.$serv_name.' - '.$serv_price,
            ];
        } else {
            $res = null;
        }

        return $res;
    }

    protected function check_client_signup(int $client_id, int $service_id, $start_dt, $master_id = null)
    {
        if (!empty($master_id)) {
            $order_isset = Order::where('client_id', $client_id)
            ->where('master_id', $master_id)
            ->where('service_id', $service_id)
            ->where('start_dt', '=', $start_dt)
            ->with('master')
            ->first();
        } else {
            $order_isset = Order::where('client_id', $client_id)
            ->where('service_id', $service_id)
            ->where('start_dt', '=', $start_dt)
            ->with('master')
            ->first();
        }

        if (!empty($order_isset->id)) {
            $master_name = (!empty($order_isset->master['master_name'])) ? $order_isset->master['master_name'] : '';
            $master_fam = (!empty($order_isset->master['master_fam'])) ? $order_isset->master['master_fam'] : '';
            $serv = Service::find($service_id);
            $page = $serv->page->title;
            $cat = $serv->category->name;
            $serv_name = $serv->name;
            $serv_price = $serv->price;
            $res = [
                'order_id' => $order_isset->id,
                'time' => $order_isset->start_dt,
                'master' => $master_name.' '.$master_fam,
                'service' => $page.', '.$cat.', '.$serv_name.' - '.$serv_price,
            ];
        } else {
            $res = null;
        }

        return $res;
    }

    /**
     * @param string $client_phone_number
     *
     * @return int
     */
    protected function get_client_id($client_phone_number, Client $client)
    {
        $cli = $client->where('phone', $client_phone_number)->first();
        if (!empty($cli->id)) {
            return $cli->id;
        } else {
            return 0;
        }
    }

    public function appoint_check(Request $request, Client $client, Master $master)
    {
        $res = null;

        $client_phone_number = $request->zapis_phone_number;
        $client_id = $this->get_client_id($client_phone_number, $client);
        $service_id = my_sanitize_number($request->usluga);
        $time = (int) $request->time / 1000;
        // $start_dt = \DateTime::createFromFormat('d-m-Y H:i:s', '16-06-2023 14:30');
        $start_dt = Carbon::createFromTimestamp($time)->toDateTimeString();
        if (!empty($request->master)) {
            $master_id = my_sanitize_number($request->master);
        }

        // check if client not already sign up
        if (!empty($master_id)) {
            $res = $this->check_client_signup($client_id, $service_id, $start_dt, $master_id);
        } else {
            $res = $this->check_client_signup($client_id, $service_id, $start_dt);
        }
        if (!empty($res)) {
            return json_encode(['res' => false, 'client_signup' => $res]);
        }

        // check if client not sign up on the same time
        $res = $this->check_client_other_signup($client_id, $start_dt);
        if (!empty($res)) {
            return json_encode(['res' => false, 'client_signup' => $res]);
        }

        // check if master is free in this time
        if (!empty($master_id)) {
            if ($this->check_free_by_master_id($master_id, $start_dt)) {
                $master_data = $master->select('master_photo', 'master_name', 'sec_name', 'master_fam')
                    ->where('id', $master_id)
                    ->first()->toArray();
                $res = ['res' => true, 'master_data' => $master_data];
            } else {
                $res = ['res' => false, 'master_busy' => true];
            }
        } else {
            if ($this->check_free_by_all_masters($start_dt)) {
                $res = ['res' => true];
            } else {
                $res = ['res' => false, 'all_master_busy' => true];
            }
        }

        return json_encode($res);
    }

    public function appoint_end(Request $request, Order $order)
    {
        if (!empty($request->dismiss)) {
            $order_dismiss = $order->find($request->dismiss);
            $time = date('H:i d.m.Y', strtotime($order_dismiss->start_dt));
            $order_dismiss->delete();

            return back()->with('dismiss', 'Запись на '.$time.' отменена.');
        } else {
            // if isset $request->last_name - spam bot
            if (empty($request->last_name)) {
                // save client data to table  'clients'
                $rules = [
                    'zapis_phone_number' => ['required', 'regex:/^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?/'],
                ];
                $messages = [
                    'zapis_phone_number.regex' => 'The phone number does not match the specified format. Телефонный номер не соответсвует формату +9 999 999 99 99',
                ];
                if (isset($request->zapis_name)) {
                    $rules['zapis_name'] = ['regex:/^[а-яА-ЯёЁa-zA-Z]+$/', 'max:255'];
                    $messages['zapis_name.max'] = 'The name is too long (255 characters allowed).';
                    $messages['zapis_name.regex'] = 'The allowed characters of name is only letters.';
                }

                $this->validate($request, $rules, $messages);

                $client = Client::firstOrCreate(['phone' => $request->zapis_phone_number], ['name' => $request->zapis_name]);

                // get duration of service and calculate the value of end_dt
                $service = Service::find($request->usluga);
                $dur = $service->duration;
                $start_dt = CarbonImmutable::createFromTimestamp($request->time / 1000);
                // if $dur > 9 - minutes, else hours
                if ($dur > 9) {
                    $end_dt = CarbonImmutable::createFromTimestamp($request->time / 1000)->addMinutes($dur);
                } else {
                    $end_dt = CarbonImmutable::createFromTimestamp($request->time / 1000)->addHours($dur);
                }

                // check if client not sign up on the same time
                $check = $this->check_client_signup($client->id, $request->usluga, $start_dt->toDateTimeString());
                // if client dont have signup (check is empty)
                if (empty($check)) {
                    // присваиваем переменным значения для записи в бд
                    $insert = [
                        'client_id' => $client->id,
                        'service_id' => $request->usluga,
                        'status' => '1',
                        'start_dt' => $start_dt->toDateTimeString(),
                        'end_dt' => $end_dt->toDateTimeString(),
                    ];
                    if (!empty($request->master)) {
                        $insert['master_id'] = $request->master;
                    } else {
                        $insert['master_id'] = null;
                    }

                    $sql_insert = $order->create($insert);

                    if (!empty($sql_insert->id)) {
                        $client_name = ($request->zapis_name) ? $request->zapis_name : '';
                        $page = $service->with('page')->find($request->usluga);
                        $page = $page['page']['title'] ?? '';
                        $cat = $service->with('category')->find($request->usluga);
                        $cat = (!empty($cat['category']['name'])) ? $cat['category']['name'] : '';
                        $serv = $service->name ?? '';
                        $price = $service->price ?? '';

                        if (!empty($request->master)) {
                            $m = Master::find($request->master);
                            $master = $m['master_name'].' '.$m['master_fam'];
                        }
                        $cyr = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
                        $month = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

                        $res = [
                            'master' => $master ?? '',
                            'client_name' => $client_name,
                            'service' => $page.', '.mb_strtolower($cat, 'UTF-8').' '.mb_strtolower($serv, 'UTF-8'),
                            'price' => $price,
                            'time' => $cyr[$start_dt->dayOfWeek].', '.$start_dt->day.' '.$month[$start_dt->month - 1].' '.$start_dt->format('Y, H:i'),
                            'client_phone' => $request->zapis_phone_number,
                        ];
                    } else {
                        $res = false;
                    }
                } else {
                    $res = false;
                }
            } else {
                $res = false;
            }

            return back()->with('res', $res);
        }
    }

    public function appoint_time(Request $request)
    {
        $master_id = null;
        if (!empty($request->master_id)) {
            $validated = $request->validate([
                'master_id' => 'numeric',
            ]);
            $master_id = $request->master_id;
        }
        $rest_day_time = $this->get_restdaytimes($master_id) ?? null;
        // get appointment by master
        $appointment = $this->get_appointment($master_id, 1) ?? null;

        if (!empty($request->service_id)) {
            $dur = Service::find($request->service_id)->duration;
        }

        // query for get woktime, lunchtime, holiday, weekdays and other
        $data = $this->getCalSet();

        $res = [
            'lehgth_cal' => $data['lehgth_cal'],
            'endtime' => $data['endtime'],
            'period' => $data['period'],
            'worktime' => $data['worktime'],
            'lunch' => $data['lunch'],
            'org_weekend' => $data['orgweekends'],
            'holiday' => $data['holidays'],
            'rest_day_time' => $rest_day_time,
            'exist_app_date_time_arr' => $appointment,
            'serv_duration' => $dur,
        ];

        return response()->json($res);
    }
}
