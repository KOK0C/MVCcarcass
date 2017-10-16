<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

namespace App;

/**
 * Class DataBase
 * @package App
 */
class DataBase
{
    use Singleton;

    private $pdo;

    /**
     * DataBase constructor.
     * Инициализируеться подключение PDO
     */
    private function __construct()
    {
        $config = Config::getInstance();
        $this->pdo = new \PDO
        (
            'mysql:host=' . $config->get('db')['host'] . ';dbname=' . $config->get('db')['db_name'],
            $config->get('db')['db_user'],
            $config->get('db')['db_pass']
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