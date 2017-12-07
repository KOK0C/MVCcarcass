<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 18.10.2017
 * Time: 18:24
 */

namespace IhorRadchenko\App;

use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\Components\Cache;
use IhorRadchenko\App\Models\Page;

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

    public function action($action, $arg1 = null, $arg2 = null, $arg3 = null)
    {
        $methodName = 'action' . ucfirst($action);
        $arg1 = is_null($arg1) ? '' : $arg1;
        $cache = new Cache();
        if (! $this->header->page = $cache->get("page-$arg1")) {
            $this->header->page = Page::findByLink($arg1);
            $cache->set("page-$arg1", $this->header->page, 3600);
        }
        $this->$methodName($arg1, $arg2, $arg3);
    }

    protected function buildHeader()
    {
        $this->header = new View('/App/templates/layouts/header.phtml');
        $cache = new Cache();
        if (! $this->header->categories = $cache->get('categories')) {
            $this->header->categories = Category::findAll();
            $cache->set('categories', $this->header->categories, 3600);
        }
    }

    protected function buildSideBar()
    {
        $this->sideBar = new View('/App/templates/layouts/sidebar.phtml');
        $cache = new Cache();
        if (! $this->sideBar->brands = $cache->get('side_bar')) {
            $this->sideBar->brands = Brand::findAll(false, 'name');
            $cache->set('side_bar', $this->sideBar->brands, 3600);
        }
    }

    protected function buildFooter()
    {
        $this->footer = new View('/App/templates/layouts/footer.phtml');
    }

    protected function isAjax(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest");
    }

    protected function isPost(string $keyPOST): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[$keyPOST]));
    }

    protected function isGet(string $keyGET): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET[$keyGET]));
    }
}