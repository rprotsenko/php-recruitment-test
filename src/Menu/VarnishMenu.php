<?php

namespace Snowdog\DevTest\Menu;

class VarnishMenu extends AbstractMenu
{
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] == '/varnish';
    }

    public function getHref()
    {
        return '/varnish';
    }

    public function getLabel()
    {
        return 'Varnish';
    }

    public function __invoke()
    {
        if(isset($_SESSION['login'])) {
            return parent::__invoke();
        }

    }
}