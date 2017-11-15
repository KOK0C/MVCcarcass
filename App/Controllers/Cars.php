<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.10.2017
 * Time: 14:29
 */

namespace App\Controllers;

use App\Controller;
use App\Exceptions\Error404;
use App\Models\Brand;
use App\Models\Car;
use App\View;

class Cars extends Controller
{
    /**
     * Шаблон страницы
     * @var View
     */
    private $mainPage;

    protected function actionOneMark(string $mark)
    {
        $this->mainPage = new View('/App/templates/mark_page.phtml');
        $mark = ucwords(str_replace('-', ' ', $mark));
        $this->mainPage->mark = Brand::findOneByMark($mark);
        $this->mainPage->cars = \App\Models\Car::findCarsByBrand($mark);
        if (empty($this->mainPage->cars)) {
            throw new Error404('Страница не найдена');
        }
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    protected function actionOneModel(string $mark, string $model)
    {
        $this->mainPage = new View('/App/templates/one_model.phtml');
        $mark = ucwords(str_replace('-', ' ', $mark));
        $model = mb_strtoupper(str_replace('-', ' ', $model));
        $this->header->page->title .= " $model";
        $this->mainPage->news = Car::findNewsForCar($model);
        $this->mainPage->car = Car::findCarByBrandAndModel($mark, $model);
        if (empty($this->mainPage->car)) {
            throw new Error404('Модель авто не найдена');
        }
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

}