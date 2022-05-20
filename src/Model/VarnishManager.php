<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnish WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function getWebsites(Varnish $varnish)
    {
        $varnishId = $varnish->getVarnishId();
        if (!$varnishId) {
            return [];
        }

        /** @var \PDOStatement $query */
        $query = $this->database->prepare("
            SELECT w.*
            FROM websites AS w
            INNER JOIN varnish_websites AS vw ON vw.website_id = w.website_id
            WHERE vw.varnish_id = :varnish
        ");

        $query->bindParam(':varnish', $varnishId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    public function getByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        if (!$websiteId) {
            return [];
        }

        /** @var \PDOStatement $query */
        $query = $this->database->prepare("
            SELECT v.*
            FROM varnish AS v
            INNER JOIN varnish_websites AS vw ON vw.varnish_id = v.varnish_id
            WHERE vw.website_id = :website
        ");
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function create(User $user, $ip)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO varnish (ip, user_id) VALUES (:ip, :user)');
        $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function link($varnishId, $websiteId)
    {
        if (!$varnishId || !$websiteId) {
            return false;
        }
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare(
            'INSERT INTO varnish_websites (varnish_id, website_id) VALUES (:varnish, :website)'
        );
        $statement->bindParam(':varnish', $varnishId, \PDO::PARAM_INT);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function unlink($varnishId, $websiteId)
    {
        if (!$varnishId || !$websiteId) {
            return false;
        }

        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare(
            'DELETE FROM varnish_websites WHERE varnish_id = :varnish AND website_id = :website'
        );
        $statement->bindParam(':varnish', $varnishId, \PDO::PARAM_INT);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return true;
    }

}
