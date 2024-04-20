<?php

namespace App\Http\Controllers;

use App\Models\TopRankModel;
use Illuminate\Http\Request;

class topRankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(){
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(TopRankModel $topRankModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TopRankModel $topRankModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TopRankModel $topRankModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TopRankModel $topRankModel)
    {
        //
    }
}
