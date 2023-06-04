<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Models\Masters;
use Illuminate\Http\Request;

class MastersController extends Controller
{
    use \App\Traits\Upload;

    /**
     * Display a listing of the resource.
     */
    public function index(Masters $masters)
    {
        if ($masters->exists()) {
            $m = $masters->whereNull('data_uvoln')->get()->toArray();
            $m_dism = $masters->whereNotNull('data_uvoln')->get()->toArray();
        }
        if (!isset($m)) {
            $m = 'Table masters is empty';
        }
        if (!isset($m_dism)) {
            $m_dism = '';
        }

        return view('admin_manikur.moder_pages.masters', ['masters' => $m, 'masters_dism' => $m_dism]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_manikur.moder_pages.masters_create_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Masters $masters)
    {
        $res = [];
        // upload image
        $this->disk = 'public';
        $this->folder = 'images'.DIRECTORY_SEPARATOR.'masters';
        $this->filename = mb_strtolower(sanitize(translit_to_lat($request->master_phone_number)));

        $request->validate([
            'image_file' => 'mimes:jpg,png,webp|max:1024000',
        ]);
        if ($request->hasfile('image_file') && $request->file('image_file')->isValid()) {
            if ($img = $this->uploadFile($request->file('image_file'))) {
                $res['img'] = 'The photo of master "'.$request->master_fam.'" has been uploaded.';
            }
        }

        $insert = [
            'master_photo' => (!empty($img)) ? $img : null,
            'master_name' => $request->master_name,
            'sec_name' => (!empty($request->sec_name)) ? $request->sec_name : null,
            'master_fam' => $request->master_fam,
            'master_phone_number' => $request->master_phone_number,
            'spec' => $request->spec,
            'data_priema' => (!empty($request->hired)) ? $request->hired : null,
            'data_uvoln' => (!empty($request->data_uvoln)) ? $request->dismissed : null,
        ];

        if (!empty($insert) && is_array($insert)) {
            $masters->create($insert);
            $res['db'] = 'The data of master "'.$request->master_fam.'" has been stored in db.';
        }

        return view('admin_manikur.moder_pages.masters', ['res' => $res]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Masters $masters)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Masters $masters)
    {
        // $res = $masters->where('id', $request->id)->get()->toArray();
        $res = $masters->where('id', $request->id)->first()->toArray();

        return view('admin_manikur.moder_pages.masters_edit', ['res' => $res]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Masters $masters)
    {
        if (!empty($request->id)) {
            $res['db'] = 'The data of master has not been changed.';
            if (!empty($request->master_name)) {
                $insert['master_name'] = $request->master_name;
            }
            if (!empty($request->sec_name)) {
                $insert['sec_name'] = $request->sec_name;
            }
            if (!empty($request->master_fam)) {
                $insert['master_fam'] = $request->master_fam;
            }
            if (!empty($request->master_phone_number)) {
                $insert['master_phone_number'] = $request->master_phone_number;
            }
            if (!empty($request->spec)) {
                $insert['spec'] = $request->spec;
            }
            if (!empty($request->data_priema)) {
                $insert['data_priema'] = $request->data_priema;
            }
            if (!empty($request->data_uvoln)) {
                $insert['data_uvoln'] = $request->data_uvoln;
            }

            // upload image
            $this->disk = 'public';
            $this->folder = 'images'.DIRECTORY_SEPARATOR.'masters';

            if ($request->hasfile('image_file') && $request->file('image_file')->isValid()) {
                $request->validate([
                    'image_file.*' => 'mimes:jpg,png,webp|max:1024000',
                ]);

                $this->filename = mb_strtolower(sanitize(translit_to_lat($request->master_phone_number)));
                $img = $this->uploadFile($request->file('image_file'));
                if (!empty($img)) {
                    $res['img'] = 'The photo of master has been uploaded.';
                    $insert['master_photo'] = $img;
                }
            }

            if (!empty($insert) && is_array($insert)) {
                if ($masters->where('id', $request->id)->update($insert) > 0) {
                    $res['db'] = 'The data of master has been updated in db.';
                }
            }
        } else {
            $res = 'The master was not selected';
        }

        return view('admin_manikur.moder_pages.masters', ['res' => $res]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Masters $masters)
    {
        $res = '';
        list($id, $image) = explode('plusplus', $request->id);
        if (!empty($id) && $masters->destroy($id)) {
            $res .= 'Masters data have been removed! ';
        } else {
            $res .= 'WARNING! Masters data have been NOT removed! ';
        }
        if (!empty($image) && $image !== 'images'.DIRECTORY_SEPARATOR.'ddd.jpg') {
            if (delete_file(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$image)) !== 'true') {
                $res .= delete_file(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$image));
            } else {
                $res .= 'Image of Master have been deleted.';
            }
        }

        return view('admin_manikur.moder_pages.masters', ['res' => $res]);
    }
}
