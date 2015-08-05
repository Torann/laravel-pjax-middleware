<?php

namespace Torann\Pjax;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class PjaxMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only handle non-redirections & PJAX requests
        if (!$response->isRedirection() && $request->pjax())
        {
            $crawler = new Crawler($response->getContent());

            // Filter to title (in order to update the browser title bar)
            $response_title = $crawler->filter('head > title');

            // Filter to given container
            $response_container = $crawler->filter($request->header('X-PJAX-CONTAINER'));

            // Container must exist
            if ($response_container->count() != 0)
            {
                $title = $containers = '';

                // If a title-attribute exists
                if ($response_title->count() != 0) {
                    $title = '<title>' . $response_title->html() . '</title>';
                }

                // Set containers
                foreach($response_container as $item) {
                    $containers .= $item->ownerDocument->saveHTML($item);
                }

                // Set new content for the response
                $response->setContent($title.$containers);
            }

            // Updating address bar with the last URL in case there were redirects
            $response->header('X-PJAX-URL', $request->getRequestUri());
        }

        return $response;
    }

}