<?php

namespace Snowdog\DevTest\Acl;

class AclRepository
{
    private static $instance = null;

    private $contextRoutes = [];

    private $restrictedRoutes = [];

    public function addContextRoute($context, $controllerClass, $method)
    {
        $this->contextRoutes[$context] = [$controllerClass, $method];
        return $this;
    }

    public function addRestrictedRoute($route, $restrictedContext)
    {
        $this->restrictedRoutes[$route] = $restrictedContext;
        return $this;
    }

    public function getRestrictedRoute($route)
    {
        foreach ($this->restrictedRoutes as $key => $restrictedContext) {
            if (preg_match('#' . $key . '#', $route, $matches)) {
                return $this->getContextRoute($restrictedContext);
            }
        }

        return [];
    }

    public function getContextRoute($context)
    {
        return $this->contextRoutes[$context] ?? null;
    }

    /**
     * @return AclRepository
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function registryContextRoute($context, $controllerClass, $method)
    {
        $instance = self::getInstance();
        $instance->addContextRoute($context, $controllerClass, $method);
    }

    public static function registryRestrictedRoute($route, $restrictedContext)
    {
        $instance = self::getInstance();
        $instance->addRestrictedRoute($route, $restrictedContext);
    }

}