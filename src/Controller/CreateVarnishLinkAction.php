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
            $request = file_get_contents("php://input");
            if (!empty($request)) {
                $data = json_decode($request, true);

                $responseText = '';

                if (isset($data['action']) && !empty($data['action'])) {
                    if (isset($data['varnishId'], $data['websiteId']) && !empty($data['varnishId']) && !empty($data['websiteId'])) {
                        switch ($data['action']) {
                            case 'link':
                                if ($this->varnishManager->link((int)$data['varnishId'], (int)$data['websiteId'])) {
                                    $responseText = 'Website with ID ' . $data['websiteId'] . ' successfully linked';
                                } else {
                                    $responseText = 'Error by linking Website ID ' . $data['websiteId'];
                                }
                                break;
                            case 'unlink':
                                if ($this->varnishManager->unlink((int)$data['varnishId'], (int)$data['websiteId'])) {
                                    $responseText = 'Website with ID ' . $data['websiteId'] . ' successfully unlinked';
                                } else {
                                    $responseText = 'Error by unlinking Website ID ' . $data['websiteId'];
                                }
                                break;
                        }
                    } else {
                        $responseText = 'Field(s) `varnishId` and/or `websiteId` should provided';
                    }
                } else {
                    $responseText = 'Field `action` should provided';
                }
                echo $responseText;
                return;
            }
        }
        header('Location: /');
    }
}
