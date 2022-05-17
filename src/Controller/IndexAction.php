<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Website;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\PageManager;

class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Website[]
     */
    private $websites = [];

    public function __construct(
        UserManager $userManager,
        WebsiteManager $websiteManager,
        PageManager $pageManager
    ) {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    protected function getWebsites()
    {
        if (empty($this->websites)) {
            if($this->user) {
                $websites = $this->websiteManager->getAllByUser($this->user);
                foreach ($websites as $website) {
                    $this->websites[$website->getWebsiteId()] = $website;
                }
            }
        }
        return $this->websites;
    }

    protected function getWebsite($websiteId)
    {
        $websites = $this->getWebsites();
        return $websites[$websiteId] ?? null;
    }

    protected function getTotalPages()
    {
        if($this->user) {
            return $this->pageManager->getTotalPagesByUser($this->user);
        }
        return null;
    }

    protected function getLeastVisitedPage()
    {
        if($this->user) {
            return $this->pageManager->getLeastVisitedPageByUser($this->user);
        }
        return null;
    }
    protected function getMostVisitedPage()
    {
        if($this->user) {
            return $this->pageManager->getMostVisitedPageByUser($this->user);
        }
        return null;
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}