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
        if ($this->check_free_by_all_masters($start_dt)) {
            $order = Order::where('master_id', $master_id)
                ->where('start_dt', '<=', $start_dt)
                ->where('end_dt', '>', $start_dt)
                ->get()
                ->toArray();

            // check if start_dt not = or between srart_dt and end_dt for each item of $order collection
            if (!empty($order)) {
                $res = false;
            } else {
                // если таблица записей к мастеру пуста - выводим форму
                $res = true;
            }
        } else {
            $res = false;
        }

        return $res;
    }

    public function appoint_phone(Request $request)
    {
        if (!empty($request->time)) {
            $time = (int) $request->time / 1000;
            // $start_dt = \DateTime::createFromFormat('d-m-Y H:i:s', '16-06-2023 14:30');
            $start_dt = Carbon::createFromTimestamp($time)->toDateTimeString();

            if (!empty($request->master)) {
                $master_id = my_sanitize_number($request->master);
                if ($this->check_free_by_master_id($master_id, $start_dt)) {
                    $res = ['res' => true];
                } else {
                    $res = ['res' => false];
                }
            } else {
                if ($this->check_free_by_all_masters($start_dt)) {
                    $res = ['res' => true];
                } else {
                    $res = ['res' => false];
                }
            }
        } else {
            $res = ['res' => 'empty'];
        }

        return json_encode($res);
    }

    public function appoint_end(Request $request, Order $order)
    {
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
            /*
            $order_isset = $order->where('client_id', $client->id)
                ->where('service_id', $request->usluga)
                ->where('master_id', $insert['master_id'])
                ->where('start_dt', '=', $start_dt->toDateTimeString())
                ->first();
            */
            // if client signed up - mes: Yu are signed up or cancel the order
            $order_isset = $order->where('client_id', $client->id)
            ->where('start_dt', '=', $start_dt->toDateTimeString())
            ->first();

            if (!empty($order_isset->id)) {
                $res = '<p style="margin: 0 auto;">Вы уже записаны на:</p>
                        <div class="table_body" style="border-collapse: collapse;">
                                <div class="table_row">
                                <div class="table_cell" style="text-align:right;">Дата,<br /> время:</div>
                                <div class="table_cell">'.$start_dt->dayOfWeek.', '.$start_dt->format('d M Y, H:i').'</div>
                            </div>
                        </div>';
            } else {
                $sql_insert = $order->create($insert);

                if (!empty($sql_insert->id)) {
                    // create sql query
                    $client_name = ($request->zapis_name) ? $request->zapis_name : '';
                    $page = $service->with('page')->find($request->usluga);
                    $page = $page['page']['title'];
                    $cat = $service->with('category')->find($request->usluga);
                    $cat = (!empty($cat['category']['name'])) ? $cat['category']['name'] : '';
                    $serv = $service->name;
                    $price = $service->price;

                    if (!empty($request->master)) {
                        $m = Master::find($request->master);
                    } else {
                        $m = ['master_name' => '', 'master_fam' => ''];
                    }
                    $cyr = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
                    $month = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

                    $res = '<h3>'.$client_name.'</h3>
                            <p><b>Вы записались на:</b></p>
                            <div class="table_body" style="border-collapse: collapse;">
                                <div class="table_row">
                                    <div class="table_cell" style="text-align:right;">'.$page.', '.mb_strtolower($cat, 'UTF-8').' '.mb_strtolower($serv, 'UTF-8').'</div>
                                    <div class="table_cell">'.$price.' руб.</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_cell" style="text-align:right;">Мастер: </div>
                                    <div class="table_cell">'.$m['master_name'].' '.$m['master_fam'].'</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_cell" style="text-align:right;">Дата,<br /> время:</div>
                                    <div class="table_cell">'.$cyr[$start_dt->dayOfWeek].', '.$start_dt->day.' '.$month[$start_dt->month - 1].' '.$start_dt->format('Y, H:i').'</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_cell" style="text-align:right;">Ваш номер:</div>
                                    <div class="table_cell">'.$request->zapis_phone_number.' </div>
                                </div>
                            </div>
                            <h3>Спасибо за ваш выбор!</h3>';
                } else {
                    $res = '<p class="error">Warning! Data of order have been NOT stored!</p>';
                }
            }
        } else {
            $res = 'Hi, bot';
        }

        return back()->with('res', $res);
    }

    public function appoint_time(Request $request)
    {
        $master_id = ($request->get('master_id')) ? $request->get('master') : null;
        if (!empty($master_id)) {
            // get appointment by master
        }
        if (!empty($request->service_id)) {
            $dur = Service::find($request->service_id)->duration;
        }

        // query for get woktime, lunchtime, holiday, weekdays and other

        $res = [
            'lehgth_cal' => null,
            'endtime' => null,
            'period' => null,
            'worktime' => null,
            'lunch' => null,
            'org_weekend' => null,
            'rest_day_time' => null,
            'holiday' => null,
            'exist_app_date_time_arr' => null,
            'serv_duration' => $dur,
        ];

        return response()->json($res);
    }
}
