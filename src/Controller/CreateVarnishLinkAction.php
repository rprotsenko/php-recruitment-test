<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\Website;

class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
    }

    public function execute()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
            $data = json_decode(file_get_contents("php://input"), true);

            $responseText = '';
            if (isset($data['varnishId'], $data['websiteId'], $data['action'])) {
                switch ($data['action']) {
                    case 'link':
                        $this->varnishManager->link($data['varnishId'], $data['websiteId']);
                        $responseText = 'Website with ID ' . $data['websiteId'] . ' successfully linked';
                        break;
                    case 'unlink':
                        $this->varnishManager->unlink($data['varnishId'], $data['websiteId']);
                        $responseText = 'Website with ID ' . $data['websiteId'] . ' successfully unlinked';
                        break;
                }
            }
            echo $responseText;
            return;
        }

        header('Location: /');
    }
}