<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminHomeController extends Controller
{
    protected function admin()
    {
        $content = [];
        foreach (Route::getRoutes()->getRoutesByMethod()['GET'] as $route) {
            if (strpos($route->uri, 'admin') !== false) {
                $content[] = $route->uri;
            }
        }

        return $content;
    }

/*
    protected function moder()
    {
        $content = [];
        foreach (Route::getRoutes()->getRoutesByMethod()['GET'] as $route) {
            if (strpos($route->uri, 'moder') !== false) {
                $content[] = $route->uri;
            }
        }

        return $content;
    }

    protected function user()
    {
        $content = [
            'recall list' => url('/').'/recall_list',
            'recall log' => url('/').'/recall_log',
            'appointment by masters' => url('/').'/appointment_by_masters',
            'appointment by dates' => url('/').'/appointment_by_dates',
        ];

        return $content;
    }
*/
    public function getview()
    {
        $method_name = Auth::user()['status'];
        $content = $this->$method_name();

        return view('admin_manikur.home_adm', ['content' => $content]);
    }
}
