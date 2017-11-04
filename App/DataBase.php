<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

namespace App;

use App\Components\Config;
use App\Components\Singleton;
use App\Exceptions\DbException;

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
     * @throws DbException
     */
    private function __construct()
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
            throw new \App\Exceptions\DbException();
        }
    }

    /**
     * @param string $sql Строка запроса к бд
     * @param array $params Массив подстановок
     * @return bool true в случае успешного запроса, иначе false
     * @throws DbException
     */
    public function execute(string $sql, array $params = []): bool
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new \App\Exceptions\DbException();
        }
    }

    /**
     * @param string $sql Строка запроса
     * @param string $className Класс для которого будут извлекаться объекты из бд
     * @param array $params Массив подстановок
     * @return array Возвращает массив с объектрами
     * @throws DbException
     */
    public function query(string $sql, string $className, array $params = []): array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new \App\Exceptions\DbException();
        }
        return $stmt->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * @return string Возвращает последний добавленный id из базы данных
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function countRow(string $sql, array $params): int
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new DbException();
        }
        return $stmt->fetchColumn();
    }
}