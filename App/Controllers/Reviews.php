<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 27.11.2017
 * Time: 17:54
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Review;
use IhorRadchenko\App\View;

class Reviews extends Controller
{
    private $mainPage;

    protected function actionIndex()
    {
        $this->mainPage = new View('/App/templates/reviews.phtml');
        $this->mainPage->reviews = Review::findAll(true);
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    protected function actionMark($page, string $mark)
    {
        $mark = ucwords(str_replace('-', ' ', $mark));
        $this->mainPage = new View('/App/templates/reviews.phtml');
        $this->header->page->title = "Отзывы об $mark";
        $this->mainPage->reviews = Review::findReviewsByBrand($mark);
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }
}