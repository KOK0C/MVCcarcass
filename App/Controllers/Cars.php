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
use App\View;

class Cars extends Controller
{
    /**
     * Шаблон страницы
     * @var View
     */
    protected $mainPage;

    public function __construct()
    {
        parent::__construct();
        $this->mainPage = new View('/App/templates/mark_page.phtml');
    }

    protected function actionOneMark(string $mark)
    {
        $mark = ucwords(str_replace('-', ' ', $mark));
        $this->mainPage->cars = \App\Models\Cars::findCarsByBrand($mark);
        if (empty($this->mainPage->cars)) {
            throw new Error404();
        }
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

}