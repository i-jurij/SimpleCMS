<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;

class SignupController extends Controller
{
    public function by_date()
    {
        return view('cadmin_manikur.moder_pages.signup', ['res' => 'signup by date']);
    }

    public function by_master()
    {
        return view('cadmin_manikur.moder_pages.signup', ['res' => 'signup by master']);
    }

    public function remove()
    {
        return view('cadmin_manikur.moder_pages.signup', ['res' => 'signup remove']);
    }
}
