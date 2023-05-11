<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePagesRequest;
use App\Http\Requests\UpdatePagesRequest;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $res = null)
    {
        $pages = (Pages::all()->toArray()) ? Pages::all()->toArray() : ['No pages in DB'];

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
     * Store a newly created resource in storage.
     */
    public function store(StorePagesRequest $request)
    {
        // upload image
        //
        // get image name
        $img = $request->alias;

        // if single_page no or false - create models, controllers, views etc

        $create = Pages::create([
            'alias' => $request->alias,
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => ($request->keywords) ? $request->keywords : '',
            'robots' => $request->robots,
            'content' => ($request->content) ? $request->content : '',
            'single_page' => $request->single_page,
            'img' => $img,
            'publish' => $request->publish,
        ]);

        return view('admin_manikur.moder_pages.pages_store', ['res' => $create->attributesToArray()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pages $pages)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pages $pages)
    {
        $data = $pages->all()->toArray();

        return view('admin_manikur.moder_pages.pages_edit_form', ['fields' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePagesRequest $request, Pages $pages)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Pages $pages)
    {
        $page_id = ($request->id) ? $request->id : false;

        if ((bool) $page_id && $pages->destroy($page_id)) {
            return $this->index('Pages data have been removed!');
        } else {
            return $this->index('WARNING! Pages data have been NOT removed!');
        }
    }
}
