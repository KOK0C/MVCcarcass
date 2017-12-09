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
use IhorRadchenko\App\Models\User as UserModel;
use IhorRadchenko\App\View;

class User extends Admin
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     * @throws Error404
     */
    protected function actionIndex()
    {
        if (UserModel::isAdmin()) {
            if ($this->isAjax() && isset($_POST['page'])) {
                View::loadForAjax('admin/users', UserModel::findPerPage($_POST['page'], UserModel::PER_PAGE));
                exit();
            }
            $this->mainPage = new View('/App/templates/admin/users.phtml');
            $this->mainPage->users = UserModel::findPerPage(1, UserModel::PER_PAGE);
            $this->mainPage->totalPages = ceil(UserModel::getAllCount() / UserModel::PER_PAGE);
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }
}