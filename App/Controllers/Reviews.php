<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 27.11.2017
 * Time: 17:54
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Models\Review;
use IhorRadchenko\App\View;

class Reviews extends Controller
{
    private $mainPage;

    protected function actionIndex()
    {
        $this->mainPage = new View('/App/templates/reviews.phtml');
        $this->mainPage->reviews = Review::findAll(true);
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }
}