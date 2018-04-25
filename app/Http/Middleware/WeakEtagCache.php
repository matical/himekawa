<?php

namespace himekawa\Http\Middleware;

use Closure;

class WeakEtagCache
{
    /**
     * Add cache related HTTP headers.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        if (! $request->isMethodCacheable() || ! $response->getContent()) {
            return $response;
        }

        $etag = md5($response->getContent());

        $response->setPublic();
        $response->setEtag($etag, true);
        $response->headers->addCacheControlDirective('must-revalidate');
        $response->headers->addCacheControlDirective('proxy-revalidate');

        $response->isNotModified($request);

        return $response;
    }
}
