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
        $content['pages_menu'] = Pages::where('publish', '=', 'yes')->get()->toArray() ?? ['No pages in DB'];

        return view('client_manikur.home', ['content' => $content]);
    }

    public function page(Pages $pages, $page_alias)
    {
        $content['contacts'] = Contacts::select('type', 'data')->get()->toArray();
        $page_data = (Pages::where('alias', $page_alias)->get()) ? Pages::where('alias', $page_alias)->get()->toArray() : ['No pages data in DB'];

        $page_data = ($pages->where('alias', $page_alias)->get()) ? $pages->where('alias', $page_alias)->get()->toArray() : ['No pages data in DB'];
        if (!empty($page_data) && !empty($page_data[0]) && !empty($page_data[0]['alias'])) {
            if ($page_data[0]['single_page'] === 'no' || $page_data[0]['service_page'] === 'yes') {
                $path = 'App\\Http\\Controllers\\Client\\';
                $class = $path.mb_ucfirst($page_alias).'Controller';
                if (class_exists($class)) {
                    if (method_exists($class, 'index')) {
                        /*
                        return redirect()->action(
                            [mb_ucfirst($page_alias).'Controller'::class, 'index'], ['content' => $content, 'page_data' => $page_data]
                        );
                        */
                        $c = new $class();

                        return response($c->index($content, $page_data));
                    } else {
                        return response('Method "index" for controller '.$class.' not exists')->header('Content-Type', 'text/plain');
                    }
                } else {
                    return response('Controller '.$class.' not exists')->header('Content-Type', 'text/plain');
                }
            } elseif (view()->exists('client_manikur.client_pages.'.$page_alias)) {
                return view('client_manikur.client_pages.'.$page_alias, ['page_data' => $page_data, 'content' => $content]);
            } else {
                return view('client_manikur.page_template', ['page_data' => $page_data, 'content' => $content]);
            }
        } else {
            abort(404);
        }
    }
}
