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

        $etag = $this->craftWeakEtag($response->getContent());

        $response->setPublic();
        $response->headers->set('etag', $etag);
        $response->headers->addCacheControlDirective('must-revalidate');
        $response->headers->addCacheControlDirective('proxy-revalidate');

        $response->isNotModified($request);

        return $response;
    }

    /**
     * Craft a weak etag.
     *
     * @param $content
     * @return string
     */
    protected function craftWeakEtag(string $content): string
    {
        return 'W/"' . md5($content) . '"';
    }
}
