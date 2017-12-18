<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 13.12.2017
 * Time: 17:02
 */

namespace IhorRadchenko\App\Controllers\Admin\CRUD;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\Transliterator;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Category;
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
            Redirect::to('/admin/articles/create');
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
            $page = new Page();
            if ($page->load(array_merge($_POST, $_FILES, ['title' => $_POST['name']]), $validRules)) {
                $page->save();
                $mark = new Brand();
                if ($mark->load(array_merge($_POST, $_FILES, ['page_id' => $page->getId()]), $validRules)) {
                    $mark->save();
                    Redirect::to('/admin/cars');
                }
                $page->delete();
            }
            Redirect::to('/admin/mark/create');
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws DbException
     * @throws Error404
     */
    protected function actionCar()
    {
        if ($this->isPost('add_car')) {
            $car = new Car();
            $validRules = [
                'model' => [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 50,
                    'unique' => 'cars'
                ],
                'text' => [
                    'required' => true
                ]
            ];
            if ($car->load(array_merge($_POST, $_FILES), $validRules)) {
                $car->save();
                Redirect::to('/admin/cars');
            }
            Redirect::to('/admin/cars/create');
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws DbException
     * @throws Error404
     */
    protected function actionCategory()
    {
        if ($this->isPost('add_category')) {
            $validRules = [
                'name' => [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 50,
                    'unique' => 'categories'
                ],
                'description_page' => [
                    'maxLength' => 255
                ]
            ];
            $page = new Page();
            if ($page->load(
                array_merge($_POST, ['name' => Transliterator::translate($_POST['name'], 'ru', 'en'), 'title' => $_POST['name']]),
                $validRules
            )) {
                $page->save();
                $category = new Category();
                if ($category->load(array_merge($_POST, ['page_id' => $page->getId()]), $validRules)) {
                    $category->save();
                    Redirect::to('/admin/categories');
                }
                $page->delete();
            }
            Redirect::to('/admin/categories');
        } else {
            throw new Error404();
        }
    }
}