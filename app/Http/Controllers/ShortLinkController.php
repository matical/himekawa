<?php

namespace himekawa\Http\Controllers;

use yuki\Repositories\WatchedAppsRepository;

class ShortLinkController extends Controller
{
    /**
     * @var \yuki\Repositories\WatchedAppsRepository
     */
    protected $watchedApps;

    public function __construct(WatchedAppsRepository $watchedApps)
    {
        $this->watchedApps = $watchedApps;
    }

    public function index()
    {
        $apps = $this->watchedApps->allAppsWithApks();

        return view('frontend.shortlinks', [
            'apps' => $apps,
        ]);
    }

    /**
     * @param $shortCode
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($shortCode)
    {
        $watched = $this->watchedApps->findBySlug($shortCode);
        $link = $watched->latestApp()->url();

        return redirect($link);
    }
}
