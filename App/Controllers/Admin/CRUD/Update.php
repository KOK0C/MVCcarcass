<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.12.2017
 * Time: 19:09
 */

namespace IhorRadchenko\App\Controllers\Admin\CRUD;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;

class Update extends Admin
{
    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     * @throws Error404
     */
    protected function actionArticle()
    {
        if ($this->isPost('update_article')) {
            $article = Article::findById($_POST['id']);
            $validRules = [
                'title' => [
                    'minLength' => 3,
                    'maxLength' => 200
                ],
                'description' => [
                    'minLength' => 3,
                    'maxLength' => 255,
                ],
                'text' => [
                    'required' => true
                ],
                'category_id' => [
                    'required' => true
                ]
            ];
            if ($_POST['title'] !== $article->title) {
                $validRules['title']['unique'] = 'news';
            }
            if ($article->load(array_merge($_POST, $_FILES), $validRules)) {
                $article->save();
                Redirect::to('/admin/articles');
            }
            Redirect::to();
        } else {
            throw new Error404();
        }
    }
}