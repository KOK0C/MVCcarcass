<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 30.10.2017
 * Time: 19:37
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Transliterator;
use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Model;

class Page extends Model
{
    const TABLE = 'pages';

    private $link;
    public $title;
    public $description;

    protected $fields = [
        'link' => '',
        'title' => '',
        'description' => ''
    ];

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Page
     * @throws DbException
     */
    public static function findByLink(string $link)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE link = :link LIMIT 1';
        $result = DataBase::getInstance()->query($sql, self::class, ['link' => $link]);
        return (! empty($result)) ? $result[0] : null;
    }

    public function load(array $data, array $rules): bool
    {
        unset($data['description']);
        $this->fields['description'] = ! empty($data['description_page']) ? $data['description_page'] : '';
        $this->fields['link'] = Transliterator::ru2Lat($data['name']);
        return parent::load($data, $rules);
    }
}