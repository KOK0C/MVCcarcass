<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:06
 */

namespace IhorRadchenko\App;

use IhorRadchenko\App\Components\Config;
use IhorRadchenko\App\Components\Traits\Singleton;
use IhorRadchenko\App\Exceptions\DbException;

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
            throw new DbException('Подключение к базе данных не удалось: ' . $e->getMessage());
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
            throw new DbException('Ошибка при запросе к бд: ' . $e->getMessage());
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
            throw new DbException('Ошибка при запросе к бд: ' . $e->getMessage());
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

    /**
     * @param string $sql
     * @param array $params
     * @return int
     * @throws DbException
     */
    public function countRow(string $sql, array $params): int
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при подсчете кол-ва записей в бд: ' . $e->getMessage());
        }
        return $stmt->fetchColumn();
    }

    /**
     * @param string $table
     * @param string $field
     * @param $value
     * @return mixed
     * @throws DbException
     */
    public function get(string $table, string $field, $value)
    {
        $sql = "SELECT * FROM $table WHERE $field = :v LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['v' => $value]);
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при извлечении записи из бд: ' . $e->getMessage());
        }
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @param string $table
     * @param array $fields
     * @return \PDOStatement
     * @throws DbException
     */
    public function insert(string $table, array $fields)
    {
        foreach ($fields as $k => $v) {
            $arr[":$k"] = $v;
        }
        $sql = "INSERT INTO $table (" . implode(', ', array_keys($fields)) . ") VALUES (" . implode(', ', array_keys($arr)) . ")";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($fields);
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при добавлении записи в бд: ' . $e->getMessage());
        }
        return $stmt;
    }

    /**
     * @param string $table
     * @param string $field
     * @param $value
     * @throws DbException
     */
    public function delete(string $table, string $field, $value)
    {
        $sql = "DELETE FROM $table WHERE $field = :v LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['v' => $value]);
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при удалении записи из бд: ' . $e->getMessage());
        }
    }
}