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

    /**
     * DataBase constructor.
     * Инициализируеться подключение PDO
     */
    private function __construct()
    {
        $this->pdo = new \PDO
        (
            'mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME,
            self::DB_USER,
            self::PASS_DB_USER
        );
    }

    /**
     * @param string $sql Строка запроса к бд
     * @param array $args Массив подстановок
     * @return bool true в случае успешного запроса, иначе false
     */
    public function execute(string $sql, array $args = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($args);
    }

    /**
     * @param string $sql Строка запроса
     * @param string $className Класс для которого будут извлекаться объекты из бд
     * @param array $args Массив подстановок
     * @return array Возращает массив с объектрами
     */
    public function query(string $sql, string $className, array $args = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * @return string Возращает последний добавленный id из базы данных
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}