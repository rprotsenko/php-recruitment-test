<?php

namespace Snowdog\DevTest\Acl\Route;

use DI\InvokerInterface;

class GuestRestrictedAction
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
        return !isset($_SESSION['login']);
    }

    public function execute(callable $routeAction, array $routeParams)
    {
        if ($this->isRestrict()) {
            header('Location: /login');
            return;
        }

        return $this->container->call($routeAction, $routeParams);
    }
}