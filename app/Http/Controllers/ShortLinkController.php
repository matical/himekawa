<?php

namespace himekawa\Http\Controllers;

use Illuminate\Http\Request;
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
        $apps = $this->watchedApps->allApps();

        return view('frontend.magiclinks', [
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
        $link = apkPath($watched->package_name, $watched->latestApp()->version_code);

        return redirect($link);
    }
}
