<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 22.10.2017
 * Time: 12:49
 */

namespace App\Controllers;

use App\Components\Cache;
use App\Models\Page;
use App\View;

class Error extends \App\Controller
{
    private $errorPage;

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
        ob_end_clean();
        $this->errorPage = new View('/App/templates/layouts/errors/errors.phtml');
        $this->errorPage->error = $message;
        View::display($this->errorPage);
    }
}