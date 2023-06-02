<?php

namespace App\Http\Controllers\UserStatus;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallbacksEditController extends Controller
{
    public function need()
    {
        /*
        $callbacks = DB::table('callbacks')
            ->where([['response', false], ['order_id', null]])
            ->join('clients', 'clients.id', '=', 'callbacks.client_id')
            ->select('callbacks.id', 'clients.name', 'clients.phone', 'callbacks.send', 'callbacks.created_at')
            ->get();

        foreach ($callbacks as $key => $object) {
            $call[$key] = (array) $object;
            $call[$key]['created_at'] = date('d.m.Y H:i', strtotime($object->created_at));
        }
        */

        $call = Callback::where('response', false)
            ->with(['client'])
            ->get();

        return view('admin_manikur.user_pages.callbacks', ['callbacks' => $call, 'stat' => 'Need to']);
    }

    public function completed(Callback $callback)
    {
        $callbacks = $callback->where('response', true)->toArray();

        return view('admin_manikur.user_pages.callbacks', ['callbacks' => $callbacks]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Callback $callback)
    {
        if (!empty($request->id)) {
            $callback->whereIn('id', $request->id)->update(['response' => true]);
        }

        return $this->need();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Callback $callback)
    {
        $res = '';
        if ($callback->destroy($request->id)) {
            $res .= 'About data have been removed!<br>';
        } else {
            $res .= 'WARNING! About data have been NOT removed!<br>';
        }
        if (!empty($request->image) && is_array($request->image)) {
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
