<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.12.2017
 * Time: 19:09
 */

namespace IhorRadchenko\App\Controllers\Admin\CRUD;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\Transliterator;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\Models\Page;

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
            Redirect::to('/admin/articles/update');
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws Error404
     * @throws DbException
     */
    protected function actionCategory()
    {
        if ($this->isPost('update_category')) {
            $validRules = [
                'name' => [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 50
                ],
                'description_page' => [
                    'maxLength' => 255
                ]
            ];
            $category = Category::findById($_POST['id']);
            $page = $category->page;
            if (! $category->name === $_POST['name']) {
                $validRules['name']['unique'] = 'categories';
            }
            if ($page->load(
                array_merge($_POST, ['name' => Transliterator::translate($_POST['name'], 'ru', 'en'), 'title' => $_POST['name']]),
                $validRules
            )) {
                $page->save();
                if ($category->load($_POST, $validRules)) {
                    $category->save();
                    Redirect::to('/admin/categories');
                }
            }
            Redirect::to('/admin/categories');
        } else {
            throw new Error404();
        }
    }
}