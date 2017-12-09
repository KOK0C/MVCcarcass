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
     * @throws Error404
     */
    protected function actionIndex()
    {
        if (User::isAdmin()) {
            if ($this->isAjax() && isset($_POST['page'])) {
                View::loadForAjax('admin/cars', CarModel::findPerPage($_POST['page'], 5));
                exit();
            }
            $this->buildMainPage();
            $this->mainPage->cars = CarModel::findPerPage(1, 5);
            $this->mainPage->totalPages = ceil(CarModel::getAllCount() / 5);
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }

    /**
     * @param $page
     * @param string $brand
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionMark($page, string $brand)
    {
        if (User::isAdmin()) {
            $brand = ucwords(str_replace('-', ' ', $brand));
            if ($this->isAjax() && isset($_POST['page'])) {
                View::loadForAjax('admin/cars', CarModel::findCarsByBrandPerPage($brand, $_POST['page'], 5));
                exit();
            }
            if (! Brand::findOneByMark($brand)) {
                throw new Error404();
            }
            $this->buildMainPage();
            $this->mainPage->cars = CarModel::findCarsByBrandPerPage($brand ,1, 5);
            $this->mainPage->totalPages = ceil(CarModel::getCountCarByBrand($brand) / 5);
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    private function buildMainPage()
    {
        $this->header->page->title .= ' | Авто';
        $this->mainPage = new View('/App/templates/admin/cars.phtml');
        $this->mainPage->brands = Brand::findAll(false, 'name');
    }
}