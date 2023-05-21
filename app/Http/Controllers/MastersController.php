<?php

namespace App\Http\Controllers;

use App\Models\Masters;
use Illuminate\Http\Request;

class MastersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Masters $masters)
    {
        if ($masters->exists()) {
            $masters->all()->toArray();
        } else {
            $masters = [];
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    public function edit(Masters $masters)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Masters $masters)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Masters $masters)
    {
    }
}
