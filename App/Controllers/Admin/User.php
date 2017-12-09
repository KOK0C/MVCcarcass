<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 13:11
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\View;

class User extends Admin
{
    private $mainPage;

    protected function actionIndex()
    {
        if (Session::has('user') && Session::get('user')->group === 'admin') {
            $this->mainPage = new View('/App/templates/admin/main.phtml');
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }
}