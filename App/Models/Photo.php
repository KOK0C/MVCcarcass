<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 01.11.2017
 * Time: 18:14
 */

namespace App\Models;

use App\DataBase;
use App\Model;

class Photo extends Model
{
    const TABLE = 'photos';

    private $article_id;
    public $file_name;

    public function getPhoto(): string
    {
        return '/public/img/photos/' . $this->file_name;
    }

    /**
     * @param int $id ID статьи
     * @return array Массив объектов Photo
     */
    public static function findForArticle(int $id): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE article_id = :id';
        $dbConnect = DataBase::getInstance();
        return $dbConnect->query($sql, self::class, ['id' => $id]);
    }
}