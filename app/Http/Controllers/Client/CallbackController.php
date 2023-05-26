<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class CallbackController extends Controller
{
    public function index($content, $page_data)
    {
        $res = ['Empty page.'];

        return view('client_manikur.client_pages.callback', ['page_data' => $page_data, 'content' => $content, 'res' => $res]);
    }
}
