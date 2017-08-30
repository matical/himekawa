<?php

namespace himekawa\Http\Controllers;

use himekawa\WatchedApp;
use himekawa\AvailableApp;
use Illuminate\Http\Request;

class AvailableAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apps = WatchedApp::all();

        return view('frontend.index')->withApps($apps);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \himekawa\AvailableApp $availableApp
     * @return \Illuminate\Http\Response
     */
    public function show(AvailableApp $availableApp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \himekawa\AvailableApp $availableApp
     * @return \Illuminate\Http\Response
     */
    public function edit(AvailableApp $availableApp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \himekawa\AvailableApp   $availableApp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AvailableApp $availableApp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \himekawa\AvailableApp $availableApp
     * @return \Illuminate\Http\Response
     */
    public function destroy(AvailableApp $availableApp)
    {
        //
    }
}
