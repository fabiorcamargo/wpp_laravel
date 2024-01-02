<?php

namespace App\Http\Controllers;

use App\Models\WppConnect;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WppConnect $wpp)
    {
        //dd($wpp);
        return view('wpp.lote.show', ['wpp' => $wpp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
