<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:56
 */

namespace App;

abstract class Model
{
    const TABLE = '';

    public $id;

    public static function findAll(): array
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . static::TABLE;
        return $dbConnect->query($sql, static::class);
    }

    public static function findById(int $id)
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id';
        $result = $dbConnect->query($sql, static::class, ['id' => $id]);
        return $result[0];
    }

    protected function isNew()
    {
        return empty($this->id);
    }

    public function save()
    {
        if ($this->isNew()) {
            $this->insert();
        } else {
            $this->update();
        }
    }

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
        $dbConnect = DataBase::getInstance();
        $dbConnect->execute($sql, $arrayProp);
        $this->id = $dbConnect->lastInsertId();
    }

    protected function update()
    {
        $arrayProp = get_object_vars($this);
        array_pop($arrayProp);
        foreach ($arrayProp as $k => $v) {
            $arraySQL[] = "$k = :$k";
        }
        $sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(', ', $arraySQL) . ' WHERE id = ' . $this->id;
        $dbConnect = DataBase::getInstance();
        $dbConnect->execute($sql, $arrayProp);
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . static::TABLE . ' WHERE id = :id';
        $dbConnect = DataBase::getInstance();
        $dbConnect->execute($sql, ['id' => $this->id]);
    }
}