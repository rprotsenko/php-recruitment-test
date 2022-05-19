<?php

namespace Snowdog\DevTest\Acl\Route;

use DI\InvokerInterface;

class UserRestrictedAction
{
    /**
     * @var InvokerInterface
     */
    private $container;

    public function __construct(InvokerInterface $container)
    {
        $this->container = $container;
    }

    public function isRestrict()
    {
        return isset($_SESSION['login']);
    }

    public function execute(callable $routeAction, array $routeParams)
    {
        if ($this->isRestrict()) {
            require __DIR__ . '/../../view/403.phtml';
            return;
        }

        return $this->container->call($routeAction, $routeParams);
    }
}