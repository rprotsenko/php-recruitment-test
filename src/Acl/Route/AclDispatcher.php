<?php

namespace Snowdog\DevTest\Acl\Route;

use FastRoute\Dispatcher;
use Snowdog\DevTest\Acl\AclRepository;

class AclDispatcher implements Dispatcher
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var AclRepository
     */
    private $aclRepository;

    public function __construct(
        Dispatcher $dispatcher,
        AclRepository $aclRepository
    ) {
        $this->dispatcher = $dispatcher;
        $this->aclRepository = $aclRepository;
    }

    /**
     * @inheritDoc
     */
    public function dispatch($httpMethod, $uri)
    {
        $route = $this->dispatcher->dispatch($httpMethod, $uri);

        if ($route[0] == self::FOUND) {
            $restrictedRoute = $this->aclRepository->getRestrictedRoute($uri);
            if (!empty($restrictedRoute)) {
                $originalRoutes = [$route[1], $route[2]];
                $route[1] = $restrictedRoute;
                $route[2] = $originalRoutes;
            }
        }
        return $route;
    }
}