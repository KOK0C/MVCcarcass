<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

namespace App;

class DataBase
{
    use Singleton;

    const HOST         = 'localhost';
    const DB_NAME      = 'car_blog';
    const DB_USER      = 'car_blog_user';
    const PASS_DB_USER = '12345';

    private $pdo;

    private function __construct()
    {
        $this->pdo = new \PDO
        (
            'mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME,
            self::DB_USER,
            self::PASS_DB_USER
        );
    }

    public function execute(string $sql, array $args = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($args);
    }

    public function query(string $sql, string $className, array $args = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, $className);
    }
}