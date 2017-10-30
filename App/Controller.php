<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 18.10.2017
 * Time: 18:24
 */

namespace App;

use App\Models\Pages;

abstract class Controller
{
    /**
     * Шаблон шапки
     * @var View
     */
    protected $header;

    /**
     * Шаблон сайдбара
     * @var View
     */
    protected $sideBar;

    /**
     * Шаблон футера
     * @var View
     */
    protected $footer;

    public function action($action, $arg1 = null, $arg2 = null)
    {
        $methodName = 'action' . ucfirst($action);
        $this->buildHeader((is_null($arg1)) ? '' : $arg1);
        $this->buildSideBar();
        $this->buildFooter();
        $this->$methodName($arg1, $arg2);
    }

    protected function buildHeader(string $link)
    {
        $this->header = new \App\View('/App/templates/layouts/header.phtml');
        $this->header->categories = \App\Models\Category::findAll();
        $this->header->page = Pages::findByLink($link);
    }

    protected function buildSideBar()
    {
        $this->sideBar = new \App\View('/App/templates/layouts/sidebar.phtml');
        $this->sideBar->brands = \App\Models\Brand::findAll();
    }

    protected function buildFooter()
    {
        $this->footer = new \App\View('/App/templates/layouts/footer.phtml');
    }
}