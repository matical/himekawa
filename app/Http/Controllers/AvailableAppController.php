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
        $apps = $watchedApps->allAppsWithApks();

        return view('frontend.index', [
            'apps' => $apps,
        ]);
    }

    public function notices()
    {
        return view('frontend.announcements', [
            'announcement' => announcement()->rendered(),
        ]);
    }

    public function faq()
    {
        return view('frontend.faq');
    }
}
