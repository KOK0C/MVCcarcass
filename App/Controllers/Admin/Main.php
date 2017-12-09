<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 08.12.2017
 * Time: 18:58
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

class Main extends Admin
{
    private $mainPage;

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        if (User::isAdmin()) {
            $this->mainPage = new View('/App/templates/admin/main.phtml');
            $this->mainPage->counts = $this->sideBar->counts;
            $this->mainPage->users = User::getLastRecord(5);
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }
}