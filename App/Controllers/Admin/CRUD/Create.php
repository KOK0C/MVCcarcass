<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 13.12.2017
 * Time: 17:02
 */

namespace IhorRadchenko\App\Controllers\Admin\CRUD;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Car;

class Create extends Admin
{
    /**
     * @throws Error404
     * @throws DbException
     */
    protected function actionArticle()
    {
        if ($this->isPost('add_article')) {
            $validRules = [
                'title' => [
                    'minLength' => 3,
                    'maxLength' => 200,
                    'unique' => 'news'
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
            $article = new Article();
            if ($article->load(array_merge($_POST, $_FILES), $validRules)) {
                $article->save();
                if (! empty($_POST['model']) && ! empty($_POST['mark']) && $car = Car::findCarByBrandAndModel(
                        str_replace('-', ' ', $_POST['mark']),
                        str_replace('-', ' ', $_POST['model'])
                    )) {
                    Article::createRelationArticleCar($article, $car);
                }
                Redirect::to('/admin/articles');
            }
            Redirect::to();
        } else {
            throw new Error404();
        }
    }
}