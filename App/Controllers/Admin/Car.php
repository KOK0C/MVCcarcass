<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 13:10
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car as CarModel;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

class Car extends Admin
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/cars', CarModel::findPerPage($_POST['page'], 5));
            exit();
        }
        $this->header->page->title .= ' | Авто';
        $this->header->breadcrumb = ['main' => 'Авто'];
        $this->mainPage = new View('/App/templates/admin/cars.phtml');
        $this->mainPage->brands = Brand::findAll(false, 'name');
        $this->mainPage->cars = CarModel::findPerPage(1, 5);
        $this->mainPage->totalPages = ceil(CarModel::getAllCount() / 5);
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @param $page
     * @param string $brand
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionMark($page, string $brand)
    {
        $brand = ucwords(str_replace('-', ' ', $brand));
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/cars', CarModel::findCarsByBrandPerPage($brand, $_POST['page'], 5));
            exit();
        }
        if (!Brand::findOneByMark($brand)) {
            throw new Error404();
        }
        $this->header->page->title .= ' | Авто';
        $this->header->breadcrumb = ['main' => 'Авто'];
        $this->mainPage = new View('/App/templates/admin/cars.phtml');
        $this->mainPage->brands = Brand::findAll(false, 'name');
        $this->mainPage->cars = CarModel::findCarsByBrandPerPage($brand, 1, 5);
        $this->mainPage->totalPages = ceil(CarModel::getCountCarByBrand($brand) / 5);
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    protected function actionCreateMark()
    {
        $this->mainPage = new View('/App/templates/admin/create/mark.phtml');
        $this->header->breadcrumb = ['main' => 'Добавление марки авто', 'child' => ['href' => '/admin/cars', 'title' => 'Авто']];
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionCreateCar()
    {
        $this->mainPage = new View('/App/templates/admin/create/car.phtml');
        $this->header->breadcrumb = ['main' => 'Добавление авто', 'child' => ['href' => '/admin/cars', 'title' => 'Авто']];
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionUpdate()
    {
        if ($this->isAjax() && isset($_POST['id'])) {
            View::loadForAjax('admin/update/cars', CarModel::findById($_POST['id']));
            exit();
        }
        throw new Error404();
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionUpdateMark()
    {
        if ($this->isPost('update_mark') && ! empty($_POST['mark'])) {
            $this->mainPage = new View('/App/templates/admin/update/mark.phtml');
            $this->mainPage->mark = Brand::findById($_POST['mark']);
            $this->header->breadcrumb = ['main' => 'Редактирование марки авто', 'child' => ['href' => '/admin/cars', 'title' => 'Авто']];
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionUpdateCar()
    {
        if ($this->isPost('update_model') && ! empty($_POST['model'])) {
            $this->mainPage = new View('/App/templates/admin/update/car.phtml');
            $this->mainPage->car = CarModel::findById($_POST['model']);
            $this->mainPage->brands = Brand::findAll(false, 'name');
            $this->header->breadcrumb = ['main' => 'Редактирование авто', 'child' => ['href' => '/admin/cars', 'title' => 'Авто']];
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }
}