<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class CallbackController extends Controller
{
    public function index($content, $page_data)
    {
        $res = null;

        return view('client_manikur.client_pages.callback', ['page_data' => $page_data, 'content' => $content, 'res' => $res]);
    }

    public function send_mail($content, $page_data)
    {
        $res = ['Mail send.'];

        // return view('client_manikur.client_pages.callback', ['page_data' => $page_data, 'content' => $content, 'res' => $res]);
        return response()->json(['res' => $res]);
    }
}
