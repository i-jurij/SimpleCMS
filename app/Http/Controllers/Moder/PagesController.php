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
    use \App\Traits\Upload;
    use \App\Traits\CreateDeleteClientPage;

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
     * Store a newly created resource in storage.
     */
    public function store(StorePagesRequest $request)
    {
        // upload image
        $this->disk = 'public';
        $this->folder = 'images/pages';
        $this->filename = $request->alias;

        $img_valid = $request->validate([
            'picture' => 'mimes:jpg,png,webp|max:1024000',
            ]);
        if ($request->hasFile('picture') && $img_valid) {
            // $this->uploadFile($request->file('picture')) from trait Upload
            $img = $this->uploadFile($request->file('picture'));
            $img_res = 'The page image has been uploaded.<br>';
        }

        // if single_page === no - create models, controllers etc
        if ($request->single_page === 'no') {
            if ($this->createNoSinglePage($request->alias)) {
                $img_res .= 'Controller, model, migration, view has been created.';
            } else {
                $img_res .= 'WARNING! Controller, model, migration, view has been NOT created.';
            }
        }

        $create = Pages::create([
            'alias' => $request->alias,
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => ($request->keywords) ? $request->keywords : '',
            'robots' => $request->robots,
            'content' => ($request->content) ? $request->content : '',
            'single_page' => $request->single_page,
            'service_page' => $request->service_page,
            'img' => ($img) ? $img : '',
            'publish' => $request->publish,
        ]);
        $res = $create->attributesToArray();

        return view('admin_manikur.moder_pages.pages_store', ['res' => $res, 'img_res' => $img_res]);
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
    public function edit(Request $request, Pages $pages)
    {
        $data = $pages->where('id', $request->id)->get()->toArray();

        return view('admin_manikur.moder_pages.page_edit_form', ['fields' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePagesRequest $request, Pages $pages)
    {
        $array = [
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => ($request->keywords) ? $request->keywords : '',
            'robots' => $request->robots,
            'content' => ($request->content) ? $request->content : '',
            'single_page' => $request->single_page,
            'service_page' => $request->service_page,
            'img' => $request->img,
            'publish' => $request->publish,
        ];
        if ($pages->alias !== $request->alias) {
            $array['alias'] = $request->alias;
        }
        if ($pages::where('id', $request->id)->update($array)) {
            return $this->index('Data of pages <b>"'.$request->alias.'"</b> have been updated!');
        } else {
            return $this->index('Data of pages <b>"'.$request->alias.'"</b> have been NOT updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Pages $pages)
    {
        $res = '';
        list($page_id, $alias, $img, $single_page) = explode('plusplus', $request->id);
        if ($single_page === 'no') {
            // delete created files, tables
            $res .= $this->delContrModMigrView($alias);
        }

        if ($pages->destroy($page_id)) {
            $res .= 'Data of page in DB <b>'.$alias.'</b> have been removed!<br>';
            /* $this->deleteFile($img, 'public') from trait Upload */
            if ($this->removeFile($img, 'public')) {
                $res .= 'Image of page <b>'.$alias.'</b> have been removed!';
            }

            return $this->index($res);
        } else {
            return $this->index('WARNING! The page <b>'.$alias.'</b> was deleted earlier or NOT removed!');
        }
    }
}
