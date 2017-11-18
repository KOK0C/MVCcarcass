<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:56
 */

namespace App;

/**
 * Class Model
 * @package App
 */
abstract class Model
{
    /**
     * @var string Хранит название таблицы в бд
     */
    const TABLE = '';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Метод достает все записи из таблицы бд и
     * возвращает их в виде объектов помещенных в массив
     * @param bool
     * @param string
     * @return array Возвращает массив с объектами
     */
    public static function findAll(bool $reversedSort = false, string $field = 'id'): array
    {
        $sql = 'SELECT * FROM ' . static::TABLE . ' ORDER BY ' . $field;
        if ($reversedSort === true) {
            $sql .= " DESC";
        }
        return DataBase::getInstance()->query($sql, static::class);
    }

    /**
     * Метод достает запись из бд по запрашиваемому id
     * и возвращает ее в виде объекта
     * @param int $id
     * @return object Возвращает запись в виде объекта из бд
     */
    public static function findById(int $id)
    {
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id LIMIT 1';
        $result = DataBase::getInstance()->query($sql, static::class, ['id' => $id]);
        return (! empty($result)) ? $result[0] : null;
    }

    /**
     * Метод определяет сохранялся ли ранее объект в бд,
     * если да - возращает false
     * @return bool Возвращает false если объект уже существует в бд
     */
    protected function isNew(): bool
    {
        return empty($this->id);
    }

    /**
     * Метод в зависимости от того, сохранялся ли объект в бд
     * записывает его в бд либо обновляет запись
     */
    public function save()
    {
        if ($this->isNew()) {
            $this->insert();
        } else {
            $this->update();
        }
    }

    /**
     * Метод записывает объект в базу данных
     * Получает массив свойст объекта (id не нужен, его генерирует бд)
     * затем отправляет запрос на добавление в бд
     * и записывает в свойство полученный из бд id
     * @return bool
     */
    protected function insert(): bool
    {
        $arrayProp = get_object_vars($this);
        array_pop($arrayProp);
        foreach ($arrayProp as $k => $v) {
            $arrayPropMod[':' . $k] = $v;
        }
        $sql = 'INSERT INTO ' . static::TABLE . ' (' .
            implode(', ', array_keys($arrayProp)) .
            ') VALUES ('.  implode(', ', array_keys($arrayPropMod)) .')';
        $dbConnect = DataBase::getInstance();
        if ($dbConnect->execute($sql, $arrayProp)) {
            $this->id = $dbConnect->lastInsertId();
            return true;
        }
        return false;

    }

    /**
     * Метод обновляет запись в бд
     * Сперва получая все свойства объекта кроме id (его не трогаем)
     * @return bool
     */
    protected function update(): bool
    {
        $arrayProp = get_object_vars($this);
        array_pop($arrayProp);
        foreach ($arrayProp as $k => $v) {
            $arraySQL[] = "$k = :$k";
        }
        $sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(', ', $arraySQL) . ' WHERE id = ' . $this->id;
        if (DataBase::getInstance()->execute($sql, $arrayProp)) {
            return true;
        }
        return false;
    }

    /**
     * Метод удаляет запись объекта из бд
     * @return bool
     */
    public function delete(): bool
    {
        $sql = 'DELETE FROM ' . static::TABLE . ' WHERE id = :id';
        if (DataBase::getInstance()->execute($sql, ['id' => $this->id])) {
            return true;
        }
        return false;
    }
}