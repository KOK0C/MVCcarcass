<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 13:11
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Review as ReviewModel;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

class Review extends Admin
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/reviews', ReviewModel::findPerPage($_POST['page'], ReviewModel::PER_PAGE));
            exit();
        }
        $this->mainPage = new View('/App/templates/admin/reviews.phtml');
        $this->mainPage->reviews = ReviewModel::findPerPage(1, ReviewModel::PER_PAGE);
        $this->mainPage->totalPages = ceil(ReviewModel::getAllCount() / ReviewModel::PER_PAGE);
        $this->header->page->title .= ' | Отзывы';
        $this->header->breadcrumb = ['main' => 'Отзывы'];
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @param $page
     * @param string $mark
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionMark($page, string $mark)
    {
        $mark = ucwords(str_replace('-', ' ', $mark));
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/reviews', ReviewModel::findReviewsByBrand($_POST['page'], $mark));
            exit();
        }
        $this->mainPage = new View('/App/templates/admin/reviews.phtml');
        $this->mainPage->cars = Car::findCarsByBrand($mark);
        if (! $this->mainPage->cars) {
            throw new Error404();
        }
        $this->mainPage->reviews = ReviewModel::findReviewsByBrand(1, $mark);
        $this->mainPage->totalPages = ceil(ReviewModel::getCountReview($mark) / ReviewModel::PER_PAGE);
        $this->header->page->title .= ' | Отзывы';
        $this->header->breadcrumb = ['main' => 'Отзывы'];
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @param $page
     * @param string $mark
     * @param string $model
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionModel($page, string $mark, string $model)
    {
        $mark = ucwords(str_replace('-', ' ', $mark));
        $model = mb_strtoupper(str_replace('-', ' ', $model));
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/reviews', ReviewModel::findReviewsByModel($_POST['page'], $mark, $model));
            exit();
        }
        $this->mainPage = new View('/App/templates/admin/reviews.phtml');
        if (! Car::findCarByBrandAndModel($mark, $model)) {
            throw new Error404();
        }
        $this->mainPage->reviews = ReviewModel::findReviewsByModel(1, $mark, $model);
        $this->mainPage->totalPages = ceil(ReviewModel::getCountReview($mark, $model) / ReviewModel::PER_PAGE);
        $this->header->page->title .= ' | Отзывы';
        $this->header->breadcrumb = ['main' => 'Отзывы'];
        $this->mainPage->cars = Car::findCarsByBrand($mark);
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionShow()
    {
        if ($this->isAjax() && ! empty($_POST['id'])) {
            View::loadForAjax('admin/review_modal', ReviewModel::findById($_POST['id']));
            exit();
        }
        throw new Error404();
    }
}