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
            throw new DbException('Подключение к базе данных не удалось');
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
            throw new DbException('Ошибка при запросе к бд');
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
            throw new DbException('Ошибка при запросе к бд');
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
            throw new DbException('Ошибка при подсчете кол-ва записей в бд');
        }
        return $stmt->fetchColumn();
    }

    public function get(string $field, string $table, $value)
    {
        $sql = "SELECT * FROM $table WHERE $field = :v LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['v' => $value]);
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при извлечении записи из бд');
        }
        return $stmt->fetch();
    }
}