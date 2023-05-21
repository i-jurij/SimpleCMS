<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Masters;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index($page_data = '')
    {
        if (About::exists()) {
            $abouts = About::all()->toArray();
        } else {
            $abouts = [];
            /*
            About::factory()
            ->count(5)
            ->create();
            $abouts = About::all()->toArray();
            */
        }

        if (Masters::exists()) {
            $masters = Masters::whereNull('data_uvoln')->get()->toArray();
        } else {
            $masters = [];
            /*
            Masters::factory()
            ->count(6)
            ->state(new Sequence(
                ['data_uvoln' => null],
                ['data_uvoln' => date('Y-m-d H:i:s', time())],
            ))
            ->create();
            $masters = Masters::all()->toArray();
            */
        }

        return view('client_manikur.client_pages.about', ['page_data' => $page_data, 'masters' => $masters, 'abouts' => $abouts]);
    }

    public function admin_index(About $abouts)
    {
        $abouts = About::all()->toArray();
        $status = request()->segment(count(request()->segments()));

        return view('admin_manikur.moder_pages.about', ['abouts' => $abouts, 'status' => $status]);
    }

    public function create(About $abouts)
    {
        $status = 'create';

        return view('admin_manikur.moder_pages.about', ['abouts' => $abouts, 'status' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return view('admin_manikur.moder_pages.about', ['res' => '']);
    }

    /**
     * Display the specified resource.
     */
    public function show(About $abouts)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        return view('admin_manikur.moder_pages.about', ['res' => '']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $abouts)
    {
        return view('admin_manikur.moder_pages.about', ['res' => '']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, About $abouts)
    {
        $res = '';
        if ($abouts->destroy($request->id)) {
            $res .= 'About data have been removed!<br>';
        } else {
            $res .= 'WARNING! About data have been NOT removed!<br>';
        }
        if (!empty($request->input['image']) && is_array($request->image)) {
            foreach ($request->image as $image) {
                if (delete_file($image) !== 'true') {
                    $res .= delete_file($image);
                } else {
                    $res .= 'Image(s) for about(s) have been deleted.<br>';
                }
            }
        }

        return view('admin_manikur.moder_pages.about', ['res' => $res]);
    }
}
