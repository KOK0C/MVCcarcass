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
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Page;

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
                Redirect::to('/admin/articles');
            }
            Redirect::to();
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws DbException
     * @throws Error404
     */
    protected function actionMark()
    {
        if ($this->isPost('add_mark')) {
            $validRules = [
                'name' => [
                    'required' => true,
                    'maxLength' => 50,
                    'unique' => 'brands'
                ],
                'description_page' => [
                    'maxLength' => 255
                ],
                'description' => [
                    'required' => true
                ]
            ];
            $mark = new Brand();
            if ($mark->load(array_merge($_POST, $_FILES), $validRules)) {
                $page = new Page();
                if ($page->load(array_merge($_POST, $_FILES), $validRules)) {
                    $page->save();
                    $mark->save();
                    Redirect::to('/admin/cars');
                }
            }
            Redirect::to();
        } else {
            throw new Error404();
        }
    }
}