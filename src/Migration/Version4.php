<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

class Version4
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
            ALTER TABLE `pages` ADD `visit_count` int(11) unsigned DEFAULT 0;
            ALTER TABLE `pages` ADD INDEX(`visit_count`);
SQL;
        $this->database->exec($query);
    }

}