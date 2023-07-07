<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\Request;

class SignupController extends Controller
{
    public function by_date()
    {
        $signup = Order::lengthcalendar()->get();
        $data['signup'] = $this->getServMas($signup)->toArray();

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function by_master()
    {
        $data['masters'] = Master::whereNull('data_uvoln')->get()->toArray();

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function post_by_master(Request $request)
    {
        if (!empty($request->get('master_id'))) {
            $signup = Order::where($request->get('master_id'))->lengthcalendar()->get();
            foreach ($signup as $key => $value) {
                $signup[$key]['service'] = $value->service;
            }
            $data['signup'] = $signup->toArray();
        } else {
            $data['signup'] = 'Error! No master id get in controller.';
        }

        return view('admin_manikur.moder_pages.signup', ['data' => $data]);
    }

    public function list()
    {
        // MAKE CHUNK RESULT AND PAGINATE
        $signup = Order::all();

        $data['signup'] = $this->getServMas($signup)->toArray();

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
            $collection[$key]['service'] = $value->service;
            $collection[$key]['master'] = $value->master;
        }

        return $collection;
    }
}
