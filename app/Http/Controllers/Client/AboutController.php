<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Pages;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class AboutController extends Controller
{
    use \App\Traits\Upload;

    // index for client about page
    public function index($content, $page_data)
    {
        $res = ['Empty page.'];

        return view('client_manikur.client_pages.about', ['page_data' => $page_data, 'content' => $content, 'res' => $res]);
    }

    public function create(About $about)
    {
        $columns = Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns($about->getTable());

        return view('admin_manikur.moder_pages.pages_create_form', ['fields' => $columns]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // upload image
        $this->disk = 'public';
        $this->folder = 'images/pages';

        $img_valid = $request->validate([
            'picture' => 'mimes:jpg,png,webp|max:1024000',
            ]);
        if ($request->hasFile('picture') && $img_valid) {
            // $this->uploadFile($request->file('picture')) from trait Upload
            $img = $this->uploadFile($request->file('picture'));
            $img_res = 'The page image has been uploaded.<br>';
        }

        $create = About::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image' => $request->input('image'),
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
    public function edit(Request $request, About $about)
    {
        $data = $about->where('id', $request->input('id'))->get()->toArray();

        return view('admin_manikur.moder_pages.about_edit_form', ['fields' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(About $about)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, About $about)
    {
    }
}
