<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 18.10.2017
 * Time: 18:24
 */

namespace App;

use App\Models\Page;

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

    public function __construct()
    {
        $this->header = new \App\View('/App/templates/layouts/header.phtml');
        $this->header->categories = \App\Models\Category::findAll();
        $this->sideBar = new \App\View('/App/templates/layouts/sidebar.phtml');
        $this->sideBar->brands = \App\Models\Brand::findAll();
        $this->footer = new \App\View('/App/templates/layouts/footer.phtml');
    }

    public function action($action, $arg1 = null, $arg2 = null)
    {
        $methodName = 'action' . ucfirst($action);
        $this->header->page = Page::findByLink((is_null($arg1) ? 'main' : $arg1));
        $this->$methodName($arg1, $arg2);
    }
}