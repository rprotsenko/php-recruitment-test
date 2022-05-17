<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function updateLastVisit(Website $website, $url)
    {
        $websiteId = (int)$website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('UPDATE pages 
                SET last_visit = CURRENT_TIMESTAMP, visit_count = visit_count + 1
                WHERE url = :url AND website_id = :website
        ');

        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function getTotalPagesByUser(User $user)
    {
        $userId = $user->getUserId();
        if (!$userId) {
            return null;
        }

        $query = $this->database->prepare("
            SELECT COUNT(*) AS total_pages FROM pages as p
            INNER JOIN websites as w ON w.website_id = p.website_id
            WHERE w.user_id = :user
        ");
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchColumn();
    }

    public function getLeastVisitedPageByUser(User $user)
    {
        $userId = $user->getUserId();
        if (!$userId) {
            return null;
        }

        $query = $this->database->prepare("
            SELECT p.* FROM pages as p
            INNER JOIN websites as w ON w.website_id = p.website_id
            WHERE w.user_id = :user AND p.visit_count = ( 
                SELECT MIN(visit_count) FROM pages as p1
                INNER JOIN websites as w1 ON w1.website_id = p1.website_id
                WHERE w1.user_id = :user
            )
            ORDER BY page_id ASC  
            LIMIT 1
        ");
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject(Page::class);
    }

    public function getMostVisitedPageByUser(User $user)
    {
        $userId = $user->getUserId();
        if (!$userId) {
            return null;
        }

        $query = $this->database->prepare("
            SELECT p.* FROM pages as p
            INNER JOIN websites as w ON w.website_id = p.website_id
            WHERE w.user_id = :user AND p.visit_count = ( 
                SELECT MAX(visit_count) FROM pages as p1
                INNER JOIN websites as w1 ON w1.website_id = p1.website_id
                WHERE w1.user_id = :user
            )
            AND p.visit_count > 0
            ORDER BY page_id DESC  
            LIMIT 1
        ");
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject(Page::class);
    }
}