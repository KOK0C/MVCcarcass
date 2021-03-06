<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.10.2017
 * Time: 17:46
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Components\Pagination;
use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Category;
use IhorRadchenko\App\View;

class Main extends Controller
{
    /**
     * Шаблон страницы
     * @var View
     */
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        $this->mainPage = new View('/App/templates/main_page.phtml');
        $this->mainPage->news = Article::findLastArticle();
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param string $link
     * @param string $alias
     * @throws Error404
     * @throws DbException
     */
    protected function actionOneArticle(string $link, string $alias)
    {
        $this->mainPage = new View('/App/templates/one_article.phtml');
        $this->mainPage->article = Article::findOneArticle($link, $alias);
        if (! $this->mainPage->article) {
            throw new Error404('Статья не найдена');
        }
        $this->header->page->title = mb_strtoupper($this->mainPage->article->title);
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param string $link
     * @param $page
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionOneCategory(string $link, $page)
    {
        $this->mainPage = new View('/App/templates/category_page.phtml');
        if (! Category::findByLink($link)) {
            throw new Error404('Несуществующая страница');
        }
        $this->mainPage->news = Article::findByCategory($link, is_null($page) ? 1 : $page, true);
        $countArticle = Article::getCountArticleInCategory($link);
        $this->mainPage->pagination = new Pagination($countArticle, is_null($page) ? 1 : $page, Article::PER_PAGE);
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @throws DbException
     * @throws Error404
     */
    protected function actionSearch()
    {
        if ($this->isGet('query')) {
            $this->mainPage = new View('/App/templates/search.phtml');
            $this->mainPage->news = (mb_strlen(trim($_GET['query'])) > 0) ? Article::search($_GET['query']) : [];
            View::display($this->header, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }
}