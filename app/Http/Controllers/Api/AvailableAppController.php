<?php

namespace himekawa\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use yuki\Repositories\WatchedAppsRepository;
use himekawa\Http\Resources\WatchedAppResource;
use himekawa\Http\Controllers\Api\BaseApiController as Controller;

class AvailableAppController extends Controller
{
    /**
     * @var \yuki\Repositories\WatchedAppsRepository
     */
    protected $watchedApps;

    /**
     * AvailableAppController constructor.
     *
     * @param \yuki\Repositories\WatchedAppsRepository $watchedApps
     */
    public function __construct(WatchedAppsRepository $watchedApps)
    {
        $this->watchedApps = $watchedApps;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return WatchedAppResource::collection($this->watchedApps->allAppsWithApks());
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list()
    {
        return WatchedAppResource::collection($this->watchedApps->allApps());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \himekawa\Http\Resources\WatchedAppResource
     */
    public function show($id)
    {
        return new WatchedAppResource($this->watchedApps->findBySlug($id));
    }
}
