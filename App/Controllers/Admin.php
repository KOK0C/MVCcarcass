<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 10:21
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Review;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

abstract class Admin extends Controller
{
    protected function buildHeader()
    {
        $this->header = new View('/App/templates/admin/layouts/header.phtml');
    }

    protected function buildFooter()
    {
        $this->footer = new View('/App/templates/admin/layouts/footer.phtml');
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function buildSideBar()
    {
        $this->sideBar = new View('/App/templates/admin/layouts/side_bar.phtml');
        $this->sideBar->counts = [
            'users'    => User::getAllCount(),
            'cars'     => Car::getAllCount(),
            'articles' => Article::getAllCount(),
            'reviews'  => Review::getAllCount()
        ];
    }
}