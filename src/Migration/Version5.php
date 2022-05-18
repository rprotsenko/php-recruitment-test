<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

class Version5
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(
        Database $database
    )
    {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->createVarnishTable();
        $this->createVarnishWebsitesTable();
    }

    private function createVarnishTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnish` (
  `varnish_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`varnish_id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `varnish_users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function createVarnishWebsitesTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnish_websites` (
  `varnish_id` int(11) unsigned NOT NULL,
  `website_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`varnish_id`, `website_id`),
  CONSTRAINT `varnish_websites_varnish_fk` FOREIGN KEY (`varnish_id`) REFERENCES `varnish` (`varnish_id`),
  CONSTRAINT `varnish_websites_websites_fk` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }
}