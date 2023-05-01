<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function list()
    {
        /*
        $pages = DB::table('pages')
            ->select('id', 'name', 'email', 'status')
            ->get();
        */
        return view('admin_manikur.adm_pages.pages_list');
    }

    public function add()
    {
        return view('admin_manikur.adm_pages.page_add');
    }

    public function remove($id)
    {
        /*
        $users = DB::table('users')
            ->delete($id);
        if ($users > 0) {
            $res = 'Users have been removed from the database.';
        } else {
            $res = 'WARNING! Users have been NOT removed from the database.';
        }
        */
        return view('admin_manikur.adm_pages.page_del');
    }
}
