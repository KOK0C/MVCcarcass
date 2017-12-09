<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 13:00
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article as ArticleModel;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\View;

class Article extends Admin
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     * @throws Error404
     */
    protected function actionIndex()
    {
        if (Session::has('user') && Session::get('user')->group === 'admin') {
            $this->mainPage = new View('/App/templates/admin/articles.phtml');
            $this->header->page->title .= ' | Статьи';
            $this->mainPage->news = ArticleModel::getLastRecord(5);
            $this->mainPage->categories = Category::findAll();
            $this->mainPage->totalPages = ceil(ArticleModel::getAllCount() / ArticleModel::PER_PAGE);
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }
}