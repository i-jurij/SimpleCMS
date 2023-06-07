<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ServicePageEditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $res = null)
    {
        $pages = (Pages::all()->toArray()) ? Pages::all()->toArray() : 'No pages in DB';

        return view('admin_manikur.moder_pages.pages', ['res' => $res, 'pages' => $pages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Pages $pages)
    {
        $columns = Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns($pages->getTable());

        return view('admin_manikur.moder_pages.pages_create_form', ['fields' => $columns]);
    }

    /**
     * Display the specified resource.
     */
    public function services_edit()
    {
        $data['service_page'] = (Pages::all()->toArray()) ? Pages::all()->toArray() : 'No pages in DB';

        return view('admin_manikur.moder_pages.service_edit', ['data' => $data]);
    }

    public function go(Request $request)
    {
        $class = new ServiceEditController();
        $data = $class->go($request);

        return view('admin_manikur.moder_pages.service_edit', ['data' => $data]);
    }
}
