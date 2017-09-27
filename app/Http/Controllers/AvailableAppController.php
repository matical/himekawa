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
}
