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
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\Models\Page;
use IhorRadchenko\App\Models\User;

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
            $_POST['update_article'] = true;
            $_POST['article_id'] = $article->getId();
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
            ) && $category->load($_POST, $validRules)) {
                $page->save();
                $category->save();
                Redirect::to('/admin/categories');
            }
            Redirect::to('/admin/categories');
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
        if ($this->isPost('update_mark')) {
            $mark = Brand::findById($_POST['id']);
            $page = $mark->page;
            $validRules = [
                'name' => [
                    'required' => true,
                    'maxLength' => 50
                ],
                'description_page' => [
                    'maxLength' => 255
                ],
                'description' => [
                    'required' => true
                ]
            ];
            if ($mark->name !== $_POST['name']) {
                $validRules['name']['unique'] = 'brands';
            }
            if ($mark->load(array_merge($_POST, $_FILES), $validRules) && $page->load(array_merge($_POST, $_FILES, ['title' => $_POST['name']]), $validRules)) {
                $page->save();
                $mark->save();
                Redirect::to('/admin/cars');
            }
            $_POST['update_mark'] = true;
            $_POST['mark'] = $mark->getId();
            Redirect::to('/admin/mark/update');
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
        if ($this->isPost('update_car')) {
            $car = Car::findById($_POST['id']);
            $validRules = [
                'model' => [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 50
                ],
                'text' => [
                    'required' => true
                ]
            ];
            if ($car->model !== $_POST['model']) {
                $validRules['model']['unique'] = 'cars';
            }
            if ($car->load(array_merge($_POST, $_FILES), $validRules)) {
                $car->save();
                Redirect::to('/admin/cars');
            }
            $_POST['update_car'] = true;
            $_POST['model'] = $car->getId();
            Redirect::to('/admin/cars/update');
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws DbException
     * @throws Error404
     */
    protected function actionUser()
    {
        if ($this->isPost('update_user')) {
            $user = User::findById($_POST['id']);
            $validRules = [
                'f_name' => [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 30,
                ],
                'l_name' => [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 40,
                ],
                'email' => [
                    'email' => true,
                    'maxLength' => 100
                ],
                'phone_number' => [
                    'length' => 13,
                    'phone' => true,
                ],
                'city' => [
                    'maxLength' => 64
                ]
            ];
            if ($user->email !== $_POST['email']) {
                $validRules['email']['unique'] = 'users';
            }
            if ($user->phone_number !== $_POST['phone_number']) {
                $validRules['phone_number']['unique'] = 'users';
            }
            if ($user->load($_POST, $validRules)) {
                $user->save();
                Redirect::to('/admin/users');
            }
            Redirect::to('/admin/users');
        } else {
            throw new Error404();
        }
    }
}