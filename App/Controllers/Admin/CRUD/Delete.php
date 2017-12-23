<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.12.2017
 * Time: 20:29
 */

namespace IhorRadchenko\App\Controllers\Admin\CRUD;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\Models\Review;
use IhorRadchenko\App\Models\User;

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

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionCategory()
    {
        if ($this->isAjax() && isset($_POST['id'])) {
            $category = Category::findById($_POST['id']);
            if ($category->delete()) {
                print 'true';
            }
            exit();
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionCar()
    {
        if ($this->isPost('delete_model') && ! empty($_POST['model'])) {
            $car = Car::findById($_POST['model']);
            $car->delete();
            Redirect::to('/admin/cars');
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionMark()
    {
        if ($this->isPost('delete_mark') && ! empty($_POST['mark'])) {
            $mark = Brand::findById($_POST['mark']);
            $mark->delete();
            Redirect::to('/admin/cars');
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionReview()
    {
        if ($this->isAjax() && ! empty($_POST['id'])) {
            $review = Review::findById($_POST['id']);
            if ($review->delete()) {
                print 'true';
            }
            exit();
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionUser()
    {
        if ($this->isAjax() && ! empty($_POST['id'])) {
            $user = User::findById($_POST['id']);
            if ($user->delete()) {
                print 'true';
            }
            exit();
        }
        throw new Error404();
    }
}