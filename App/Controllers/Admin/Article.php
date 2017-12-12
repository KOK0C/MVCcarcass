<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 13:00
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article as ArticleModel;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

class Article extends Admin
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/articles', ArticleModel::findPerPage($_POST['page'], ArticleModel::PER_PAGE));
            exit();
        }
        $this->mainPage = new View('/App/templates/admin/articles.phtml');
        $this->header->page->title .= ' | Статьи';
        $this->header->breadcrumb = ['main' => 'Cтатьи'];
        $this->mainPage->categories = Category::findAll();
        $this->mainPage->news = ArticleModel::getLastRecord(5);
        $this->mainPage->totalPages = ceil(ArticleModel::getAllCount() / ArticleModel::PER_PAGE);
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @param $page
     * @param string $category
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionCategory($page, string $category)
    {
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/articles', ArticleModel::findByCategory($category, $_POST['page']));
            exit();
        }
        $this->mainPage = new View('/App/templates/admin/articles.phtml');
        $this->header->page->title .= ' | Статьи';
        $this->header->breadcrumb = ['main' => 'Cтатьи'];
        $this->mainPage->categories = Category::findAll();
        $this->mainPage->news = ArticleModel::findByCategory($category, 1);
        if (! $this->mainPage->news) {
            throw new Error404('Несуществующая страница');
        }
        $this->mainPage->totalPages = ceil(ArticleModel::getCountArticleInCategory($category) / ArticleModel::PER_PAGE);
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionCreate()
    {
        if ($this->isAjax() && isset($_POST['mark'])) {
            $mark = ucwords(str_replace('-', ' ', $_POST['mark']));
            $data = Car::findCarsByBrand($mark);
            print json_encode($data, JSON_UNESCAPED_UNICODE);
            exit();
        }
        $this->mainPage = new View('/App/templates/admin/create/article.phtml');
        $this->header->page->title .= ' | Создание статьи';
        $this->header->breadcrumb = ['main' => 'Создание', 'child' => ['href' => '/admin/articles', 'title' => 'Статьи']];
        $this->mainPage->categories = Category::findAll();
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }
}