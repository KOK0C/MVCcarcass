<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 02.12.2017
 * Time: 12:19
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Controller;
use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Exceptions\Error404;

class Check extends Controller
{
    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     * @throws Error404
     */
    protected function actionEmail()
    {
        if ($this->isAjax() && isset($_POST['email'])) {
            if (empty(DataBase::getInstance()->get('users', 'email', trim($_POST['email'])))) {
                print 'true';
                exit();
            }
            print 'false';
            exit();
        }
        throw new Error404();
    }
}