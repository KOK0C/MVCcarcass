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
    public $id;

    /**
     * Метод достает все записи из таблицы бд и
     * возвращает их в виде объектов помещенных в массив
     * @return array Возвращает массив с объектами
     */
    public static function findAll(): array
    {
        $dbConnect = new DataBase();
        $sql = 'SELECT * FROM ' . static::TABLE;
        return $dbConnect->query($sql, static::class);
    }

    /**
     * Метод достает запись из бд по запрашиваемому id
     * и возвращает ее в виде объекта
     * @param int $id
     * @return object Возвращает запись в виде объекта из бд
     */
    public static function findById(int $id)
    {
        $dbConnect = new DataBase();
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id';
        $result = $dbConnect->query($sql, static::class, ['id' => $id]);
        return $result[0];
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
     */
    protected function insert()
    {
        $arrayProp = get_object_vars($this);
        array_pop($arrayProp);
        foreach ($arrayProp as $k => $v) {
            $arrayPropMod[':' . $k] = $v;
        }
        $sql = 'INSERT INTO ' . static::TABLE . ' (' .
            implode(', ', array_keys($arrayProp)) .
            ') VALUES ('.  implode(', ', array_keys($arrayPropMod)) .')';
        $dbConnect = new DataBase();
        $dbConnect->execute($sql, $arrayProp);
        $this->id = $dbConnect->lastInsertId();
    }

    /**
     * Метод обновляет запись в бд
     * Сперва получая все свойства объекта кроме id (его не трогаем)
     */
    protected function update()
    {
        $arrayProp = get_object_vars($this);
        array_pop($arrayProp);
        foreach ($arrayProp as $k => $v) {
            $arraySQL[] = "$k = :$k";
        }
        $sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(', ', $arraySQL) . ' WHERE id = ' . $this->id;
        $dbConnect = new DataBase();
        $dbConnect->execute($sql, $arrayProp);
    }

    /**
     * Метод удаляет запись объекта из бд
     */
    public function delete()
    {
        $sql = 'DELETE FROM ' . static::TABLE . ' WHERE id = :id';
        $dbConnect = new DataBase();
        $dbConnect->execute($sql, ['id' => $this->id]);
    }
}