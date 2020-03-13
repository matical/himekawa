<?php

namespace Tests\Feature;

use Tests\TestCase;
use himekawa\Http\Middleware\HttpTwoPushMiddleware;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HttpTwoHeaderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $queryStrings;

    protected function setUp(): void
    {
        parent::setUp();
        $this->injectMiddleware();
        $this->seed();
        $this->queryStrings = $this->styleAndScriptQueryStrings();
    }

    /** @test */
    public function it_sets_link_headers()
    {
        $response = $this->get('/');

        $expectedLink = sprintf('<%s>; rel=preload; as=style,<%s>; rel=preload; as=script', $this->queryStrings['/css/app.css'], $this->queryStrings['/js/app.js']);
        $response->assertHeader('Link', $expectedLink);
    }

    protected function injectMiddleware(): void
    {
        $appInstance = $this->app->make(HttpKernel::class)
                                 ->getApplication();
        $appInstance['router']->pushMiddlewareToGroup('web', HttpTwoPushMiddleware::class);
    }

    protected function styleAndScriptQueryStrings()
    {
        return collect(json_decode(file_get_contents(
            public_path('mix-manifest.json')
        )))->only(['/js/app.js', '/css/app.css']);
    }
}
