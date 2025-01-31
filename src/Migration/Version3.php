<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(
        Database $database
    ) {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->updatePagesTable();
    }

    private function updatePagesTable()
    {
        $query = <<<SQL
            ALTER TABLE `pages` ADD `last_visit` TIMESTAMP NULL;
            ALTER TABLE `pages` ADD UNIQUE(`url`, `website_id`);
SQL;
        $this->database->exec($query);
    }
}