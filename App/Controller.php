<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 18.10.2017
 * Time: 18:24
 */

namespace App;

use App\Components\Cache;
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
        $this->buildHeader();
        $this->buildSideBar();
        $this->buildFooter();
    }

    public function action($action, $arg1 = null, $arg2 = null)
    {
        $methodName = 'action' . ucfirst($action);
        $arg1 = is_null($arg1) ? 'main' : $arg1;
        $cache = new Cache();
        if (! $this->header->page = $cache->get("page-$arg1")) {
            $this->header->page = Page::findByLink($arg1);
            $cache->set("page-$arg1", $this->header->page, 3600);
        }
        $this->$methodName($arg1, $arg2);
    }

    protected function buildHeader()
    {
        $this->header = new \App\View('/App/templates/layouts/header.phtml');
        $cache = new Cache();
        if (! $this->header->categories = $cache->get('categories')) {
            $this->header->categories = \App\Models\Category::findAll();
            $cache->set('categories', $this->header->categories, 3600);
        }
    }

    protected function buildSideBar()
    {
        $this->sideBar = new \App\View('/App/templates/layouts/sidebar.phtml');
        $cache = new Cache();
        if (! $this->sideBar->brands = $cache->get('side_bar')) {
            $this->sideBar->brands = \App\Models\Brand::findAll();
            $cache->set('side_bar', $this->sideBar->brands, 3600);
        }
    }

    protected function buildFooter()
    {
        $this->footer = new \App\View('/App/templates/layouts/footer.phtml');
    }
}