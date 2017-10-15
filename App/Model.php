<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:56
 */

namespace App;


use App\Models\User;

abstract class Model
{
    const TABLE = '';

    protected $id;

    public static function findAll(): array
    {
        $dbConnect = DataBase::getInstance();
        return $dbConnect->query('SELECT * FROM ' . static::TABLE, static::class);
    }

    public static function findById(int $id): self
    {
        $dbConnect = DataBase::getInstance();
        $result = $dbConnect->query(
            'SELECT * FROM ' . static::TABLE . ' WHERE id = :id',
            static::class,
            ['id' => $id]
        );
        return $result[0];
    }
}