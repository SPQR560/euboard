<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/http-cache/{uri<.*>}", methods={"PURGE","GET"})
     */
    public function purgeHttpCache(KernelInterface $kernel, Request $request, string $uri): Response
    {
        //curl -I -X PURGE -u admin:123546 `symfony var:export SYMFONY_PROJECT_DEFAULT_ROUTE_URL`/admin/http-cache/board_sidebar
        if ('prod' === $kernel->getEnvironment()) {
            return new Response('not for production', 400);
        }

        $store = (new class($kernel) extends HttpCache {})->getStore();
        $store->purge($request->getSchemeAndHttpHost().'/'.$uri);

        return new Response('Done');
    }
}
