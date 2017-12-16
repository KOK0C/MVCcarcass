<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.10.2017
 * Time: 14:29
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\View;

class Cars extends Controller
{
    /**
     * Шаблон страницы
     * @var View
     */
    private $mainPage;

    /**
     * @param string $mark
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionOneMark(string $mark)
    {
        $this->mainPage = new View('/App/templates/mark_page.phtml');
        $mark = ucwords(str_replace('-', ' ', $mark));
        $this->mainPage->mark = Brand::findOneByMark($mark);
        if (! $this->mainPage->mark) {
            throw new Error404('Страница не найдена');
        }
        $this->mainPage->cars = Car::findCarsByBrand($mark);
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param string $mark
     * @param string $model
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionOneModel(string $mark, string $model)
    {
        $this->mainPage = new View('/App/templates/one_model.phtml');
        $mark = ucwords(str_replace('-', ' ', $mark));
        $model = mb_strtoupper(str_replace('-', ' ', $model));
        $this->mainPage->car = Car::findCarByBrandAndModel($mark, $model);
        if (! $this->mainPage->car) {
            throw new Error404('Модель авто не найдена');
        }
        $this->header->page->title .= " $model";
        $this->mainPage->news = Article::findNewsForCar($model, 1, 1);
        $this->mainPage->totalPage = ceil(Car::getCountArticleForCar($model) / Car::PER_PAGE);
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionShowNews()
    {
        if ($this->isAjax() && isset($_POST['page']) && isset($_POST['model'])) {
            View::loadForAjax('car_news', Article::findNewsForCar($_POST['model'], $_POST['page'], 1));
            exit();
        }
        throw new Error404();
    }
}