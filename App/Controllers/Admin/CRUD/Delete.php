<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.12.2017
 * Time: 20:29
 */

namespace IhorRadchenko\App\Controllers\Admin\CRUD;

use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;

class Delete extends Admin
{
    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionArticle()
    {
        if ($this->isAjax() && isset($_POST['id'])) {
            $article = Article::findById($_POST['id']);
            if ($article->delete()) {
                print 'true';
            }
            exit();
        }
        throw new Error404();
    }
}