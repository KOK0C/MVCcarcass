<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 13:10
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car as CarModel;
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
        if (Session::has('user') && Session::get('user')->group === 'admin') {
            if ($this->isAjax() && isset($_POST['page'])) {
                View::loadForAjax('admin/cars', CarModel::findPerPage($_POST['page'], 5));
                exit();
            }
            $this->mainPage = new View('/App/templates/admin/cars.phtml');
            $this->mainPage->brands = Brand::findAll(false, 'name');
            $this->mainPage->cars = CarModel::findPerPage(1, 5);
            $this->mainPage->totalPages = ceil(CarModel::getAllCount() / 5);
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }
}