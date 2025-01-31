<?php

use Snowdog\DevTest\Acl\AclRepository;
use Snowdog\DevTest\Acl\Route\GuestRestrictedAction;
use Snowdog\DevTest\Acl\Route\UserRestrictedAction;
use Snowdog\DevTest\Command\MigrateCommand;
use Snowdog\DevTest\Command\WarmCommand;
use Snowdog\DevTest\Component\CommandRepository;
use Snowdog\DevTest\Component\Menu;
use Snowdog\DevTest\Component\Migrations;
use Snowdog\DevTest\Component\RouteRepository;
use Snowdog\DevTest\Controller\CreatePageAction;
use Snowdog\DevTest\Controller\CreateVarnishAction;
use Snowdog\DevTest\Controller\CreateVarnishLinkAction;
use Snowdog\DevTest\Controller\CreateWebsiteAction;
use Snowdog\DevTest\Controller\IndexAction;
use Snowdog\DevTest\Controller\LoginAction;
use Snowdog\DevTest\Controller\LoginFormAction;
use Snowdog\DevTest\Controller\LogoutAction;
use Snowdog\DevTest\Controller\RegisterAction;
use Snowdog\DevTest\Controller\RegisterFormAction;
use Snowdog\DevTest\Controller\VarnishesAction;
use Snowdog\DevTest\Controller\WebsiteAction;
use Snowdog\DevTest\Menu\LoginMenu;
use Snowdog\DevTest\Menu\RegisterMenu;
use Snowdog\DevTest\Menu\VarnishMenu;
use Snowdog\DevTest\Menu\WebsitesMenu;

RouteRepository::registerRoute('GET', '/', IndexAction::class, 'execute');
RouteRepository::registerRoute('GET', '/login', LoginFormAction::class, 'execute');
RouteRepository::registerRoute('POST', '/login', LoginAction::class, 'execute');
RouteRepository::registerRoute('GET', '/logout', LogoutAction::class, 'execute');
RouteRepository::registerRoute('GET', '/register', RegisterFormAction::class, 'execute');
RouteRepository::registerRoute('POST', '/register', RegisterAction::class, 'execute');
RouteRepository::registerRoute('GET', '/website/{id:\d+}', WebsiteAction::class, 'execute');
RouteRepository::registerRoute('POST', '/website', CreateWebsiteAction::class, 'execute');
RouteRepository::registerRoute('POST', '/page', CreatePageAction::class, 'execute');
RouteRepository::registerRoute('GET', '/varnish', VarnishesAction::class, 'execute');
RouteRepository::registerRoute('POST', '/varnish', CreateVarnishAction::class, 'execute');
RouteRepository::registerRoute('POST', '/varnish-link', CreateVarnishLinkAction::class, 'execute');

CommandRepository::registerCommand('migrate_db', MigrateCommand::class);
CommandRepository::registerCommand('warm [id]', WarmCommand::class);

Menu::register(LoginMenu::class, 200);
Menu::register(RegisterMenu::class, 250);
Menu::register(VarnishMenu::class, 20);
Menu::register(WebsitesMenu::class, 10);

Migrations::registerComponentMigration('Snowdog\\DevTest', 5);

AclRepository::registryContextRoute('guest', GuestRestrictedAction::class, 'execute');
AclRepository::registryContextRoute('user', UserRestrictedAction::class, 'execute');

AclRepository::registryRestrictedRoute('/login', 'user');
AclRepository::registryRestrictedRoute('/register', 'user');

AclRepository::registryRestrictedRoute('/logout', 'guest');
AclRepository::registryRestrictedRoute('/website/\d+', 'guest');
AclRepository::registryRestrictedRoute('/website', 'guest');
AclRepository::registryRestrictedRoute('/page', 'guest');
AclRepository::registryRestrictedRoute('/varnish', 'guest');
AclRepository::registryRestrictedRoute('/varnish-link', 'guest');
AclRepository::registryRestrictedRoute('/import', 'guest');
