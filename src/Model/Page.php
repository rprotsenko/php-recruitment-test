<?php

namespace Snowdog\DevTest\Model;

class Page
{

    public $page_id;
    public $url;
    public $website_id;
    public $last_visit;
    public $visit_count;

    public function __construct()
    {
        $this->website_id = intval($this->website_id);
        $this->page_id = intval($this->page_id);
        $this->visit_count = intval($this->visit_count);
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @return string
     */
    public function getLastVisit()
    {
        return $this->last_visit;
    }

    /**
     * @return int
     */
    public function getVisitCount()
    {
        return $this->visit_count;
    }
    
}