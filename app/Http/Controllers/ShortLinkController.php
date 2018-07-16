<?php

namespace himekawa\Http\Controllers;

use yuki\Repositories\WatchedAppsRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShortLinkController extends Controller
{
    /**
     * @var \yuki\Repositories\WatchedAppsRepository
     */
    protected $watchedApps;

    /**
     * ShortLinkController constructor.
     *
     * @param \yuki\Repositories\WatchedAppsRepository $watchedApps
     */
    public function __construct(WatchedAppsRepository $watchedApps)
    {
        $this->watchedApps = $watchedApps;
    }

    /**
     * @return \Illuminate\Http\Response
     */
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

        // Since short links are accessible from '/'
        if ($watched === null) {
            throw new NotFoundHttpException();
        }

        $to = $watched->latestApp()->url() ?? route('index');

        return redirect($to);
    }
}
