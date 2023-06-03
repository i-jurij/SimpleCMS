<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

// menu (list of routes to admin pages)
class AdminHomeController extends Controller
{
    use \App\Traits\PagesRoutesHierarchyArray;

    public function getview()
    {
        // $content = $this->list_of_admin_routes();
        $content = $this->get_pages_menu('admin');
        $collection_of_routes = $this->routs_hierarchie();

        return view('admin_manikur.home_adm', ['content' => $content, 'routes' => $collection_of_routes]);
    }
}
