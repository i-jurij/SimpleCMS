<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Order;
use App\Models\Page;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function by_date(Request $request)
    {
        $signup = Order::lengthcalendar()->get();
        $data['by_date'] = $this->getServMas($signup);

        $date = (!empty($request->next)) ? $request->next : ((!empty($request->prev)) ? $request->prev : date('Y-m-d'));

        return view('admin_manikur.moder_pages.signup', ['data' => $data, 'dateprevnext' => htmlentities($date)]);
    }

    public function by_master()
    {
        $masters = Master::whereNull('data_uvoln')->get();

        $data['by_master'] = $masters->toArray();

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function post_by_master(Request $request)
    {
        if (!empty($request->master_id)) {
            $signup = Order::where('master_id', $request->master_id)->lengthcalendar()->get();
            /*
            foreach ($signup as $key => $value) {
                $signup[$key]['service'] = $value->service;
                $signup[$key]['client'] = $value->client;
            }
            $data['post_by_master'] = $signup->toArray();
            */
            $data['post_by_master'] = $this->getServMas($signup);
        } else {
            $data['post_by_master'] = 'Error! No master id get in controller.';
        }

        // return view('admin_manikur.moder_pages.signup', ['data' => $data]);
        return response()->json($data);
    }

    public function list()
    {
        // MAKE CHUNK RESULT AND PAGINATE
        $signup = Order::all();

        $data['list'] = $this->getServMas($signup);

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function add()
    {
        return view('admin_manikur.moder_pages.signup', ['res' => 'signup remove']);
    }

    public function remove()
    {
        return view('admin_manikur.moder_pages.signup', ['res' => 'signup remove']);
    }

    protected function getServMas(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            $service_data = $value->service;
            if (!empty($service_data)) {
                $page = Page::find($service_data['page_id'])->title;
                if (!empty($service_data['category_id'])) {
                    $category_data = ServiceCategory::find($service_data['category_id'])->name;
                }
                $category = (!empty($category_data)) ? $category_data.', ' : '';
                $service = $page.', '
                    .$category
                    .$service_data['name'].', '
                    .$service_data['duration'].' мин., '
                    .$service_data['price'].' руб.';
            } else {
                $service = '';
            }
            $master_data = $value->master;
            $master = $master_data['master_name'].' '
                .$master_data['sec_name'].' '
                .$master_data['master_fam'].'<br>'
                .$master_data['master_phone_number'];

            $client_data = $value->client;
            $client_name = (!empty($client_data['name'])) ? $client_data['name'] : 'noname';
            $client = 'Клиент: '.$client_name.', <span style="white-space:nowrap;"> '.$client_data['phone'].'</span>';

            $res[$master][] = [
                'order_id' => $collection[$key]['id'],
                'start_dt' => $collection[$key]['start_dt'],
                'service' => $service,
                'client' => $client,
            ];
        }

        return $res;
    }
}
