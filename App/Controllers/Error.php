<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 22.10.2017
 * Time: 12:49
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Components\Cache;
use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Models\Page;
use IhorRadchenko\App\View;

class Error extends Controller
{
    private $errorPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionPage404()
    {
        $this->errorPage = new View('/App/templates/layouts/errors/error404.phtml');
        $cache = new Cache();
        if (! $this->header->page = $cache->get('page-404')) {
            $this->header->page = Page::findByLink('404');
            $cache->set('page-404', $this->header->page, 3600);
        }
        View::display($this->header, $this->errorPage, $this->sideBar, $this->footer);
    }

    protected function actionError(string $message)
    {
        if (ob_get_status()) {
            ob_end_clean();
        }
        $this->errorPage = new View('/App/templates/layouts/errors/errors.phtml');
        $this->errorPage->error = $message;
        View::display($this->errorPage);
    }
}