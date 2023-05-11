<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contacts;
use App\Models\Pages;

// menu (list of routes to admin pages)
class ClientHomeController extends Controller
{
    public function index()
    {
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        $content['pages_menu'] = (Pages::all()->toArray()) ? Pages::all()->toArray() : ['No pages in DB'];

        return view('client_manikur.home', ['content' => $content]);
    }
}
