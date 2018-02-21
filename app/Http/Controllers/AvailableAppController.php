<?php

namespace himekawa\Http\Controllers;

use yuki\Repositories\WatchedAppsRepository;

class AvailableAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \yuki\Repositories\WatchedAppsRepository $watchedApps
     * @return \Illuminate\Http\Response
     */
    public function index(WatchedAppsRepository $watchedApps)
    {
        $apps = $watchedApps->allApps();

        return view('frontend.index', [
            'apps' => $apps,
        ]);
    }
}
