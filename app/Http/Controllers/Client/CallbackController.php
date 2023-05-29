<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contacts;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CallbackController extends Controller
{
    public function index($content, $page_data)
    {
        $res = null;
        $files = Storage::disk('public')->files('images'.DIRECTORY_SEPARATOR.'captcha_imgs');
        $randomArray = [];

        // 9 - number of images in captcha in callback.blade.php
        while (count($randomArray) < 6) {
            $randomKey = mt_rand(0, count($files) - 1);
            $randomArray[$randomKey] = $files[$randomKey];
        }
        $randomArray = array_values($randomArray);

        return view('client_manikur.client_pages.callback', ['page_data' => $page_data, 'content' => $content, 'res' => $res, 'captcha_imgs' => $randomArray]);
    }

    public function send_mail(Request $request)
    {
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        $page_data = (Pages::where('alias', 'callback')->get()) ? Pages::where('alias', 'callback')->get()->toArray() : ['No pages data in DB'];
        // $res = ['Mail send.'];
        $res = ['Ваша заявка принята. Ожидайте звонка...'];

        $this->validate($request, [
            'phone_number' => 'required',
            'captcha' => 'required|captcha',
        ], [
            'captcha.captcha' => 'Invalid captcha code.',
        ]);

        // return view('client_manikur.client_pages.callback', ['page_data' => $page_data, 'content' => $content, 'res' => $res]);
        return response()->json($res);
    }
}
