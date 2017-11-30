<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:56
 */

namespace IhorRadchenko\App;
use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\Validation\ValidationErrorHandler;
use IhorRadchenko\App\Components\Validation\Validator;
use IhorRadchenko\App\Exceptions\DbException;

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
    protected $fields = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    private function create_json($data)
    {
        if (is_array($data)) {
            $arr = [];
            foreach ($data as $key => $value) {
                $arr[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            return $arr;
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function load(array $data, array $rules): bool
    {
        $validator = new Validator((new ValidationErrorHandler()));
        $validation = $validator->check($data, $rules);
        if (! $validation->fails()) {
            foreach ($this->fields as $key => $value) {
                if (array_key_exists($key, $data)) {
                    $this->fields[$key] = $data[$key];
                }
            }
            return true;
        }
        Session::set('errors', $this->create_json($validation->errors()->get()));
        return false;
    }

    /**
     * Метод достает все записи из таблицы бд и
     * возвращает их в виде объектов помещенных в массив
     * @param bool
     * @param string
     * @return array Возвращает массив с объектами
     * @throws DbException
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
     * @throws DbException
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
     * @throws DbException
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
     * @throws DbException
     */
    protected function insert(): bool
    {
        $arrayPropMod = [];
        foreach ($this->fields as $k => $v) {
            $arrayPropMod[':' . $k] = $v;
        }
        $sql = 'INSERT INTO ' . static::TABLE . ' (' .
            implode(', ', array_keys($this->fields)) .
            ') VALUES ('.  implode(', ', array_keys($arrayPropMod)) .')';
        $dbConnect = DataBase::getInstance();
        if ($dbConnect->execute($sql, $this->fields)) {
            $this->id = $dbConnect->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Метод обновляет запись в бд
     * Сперва получая все свойства объекта кроме id (его не трогаем)
     * @return bool
     * @throws DbException
     */
    protected function update(): bool
    {
        $arraySQL = [];
        $arrayProp = [];
        foreach ($this->fields as $k => $v) {
            if (! empty($this->fields[$k])) {
                $arrayProp[$k] = $v;
                $arraySQL[] = "$k = :$k";
            }
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
     * @throws DbException
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