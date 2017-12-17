<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 17.12.2017
 * Time: 14:41
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Category as CategoryModel;
use IhorRadchenko\App\View;

class Category extends Admin
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/categories', CategoryModel::findPerPage($_POST['page'], CategoryModel::PER_PAGE));
            exit();
        }
        $this->mainPage = new View('/App/templates/admin/categories.phtml');
        $this->header->page->title .= ' | Категории';
        $this->header->breadcrumb = ['main' => 'Категории'];
        $this->mainPage->categories = CategoryModel::findPerPage(1, CategoryModel::PER_PAGE);
        $this->mainPage->totalPages = ceil(CategoryModel::getAllCount() / CategoryModel::PER_PAGE);
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     * @throws Error404
     */
    protected function actionUpdate()
    {
        if ($this->isAjax() && isset($_POST['id'])) {
            View::loadForAjax('admin/update/categories', CategoryModel::findById($_POST['id']));
            exit();
        }
        throw new Error404();
    }
}