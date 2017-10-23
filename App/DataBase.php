<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

namespace App;
use App\Components\Config;
use App\Exceptions\Db;
use App\Exceptions\Error404;

/**
 * Class DataBase
 * @package App
 */
class DataBase
{
    private $pdo;

    /**
     * DataBase constructor.
     * Инициализируеться подключение PDO
     * @throws Db
     */
    public function __construct()
    {
        $config = Config::getInstance();
        try {
            $this->pdo = new \PDO
            (
                'mysql:host=' . $config->db['host'] . ';dbname=' . $config->db['db_name'],
                $config->db['db_user'],
                $config->db['db_pass']
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \App\Exceptions\Db();
        }
    }

    /**
     * @param string $sql Строка запроса к бд
     * @param array $args Массив подстановок
     * @return bool true в случае успешного запроса, иначе false
     * @throws Db
     */
    public function execute(string $sql, array $args = []): bool
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($args);
        } catch (\PDOException $e) {
            throw new \App\Exceptions\Db();
        }
    }

    /**
     * @param string $sql Строка запроса
     * @param string $className Класс для которого будут извлекаться объекты из бд
     * @param array $args Массив подстановок
     * @return array Возвращает массив с объектрами
     * @throws Db
     */
    public function query(string $sql, string $className, array $args = []): array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($args);
        } catch (\PDOException $e) {
            throw new \App\Exceptions\Db();
        }
        return $stmt->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * @return string Возвращает последний добавленный id из базы данных
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}