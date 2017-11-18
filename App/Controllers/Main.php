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
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Article;
use IhorRadchenko\App\View;

class Main extends Controller
{
    /**
     * Шаблон страницы
     * @var View
     */
    private $mainPage;

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
     */
    protected function actionOneArticle(string $link, string $alias)
    {
        $this->mainPage = new View('/App/templates/one_article.phtml');
        $this->mainPage->article = Article::findOneArticle($link, $alias);
        if (! $this->mainPage->article) {
            throw new Error404('Статья не найдена');
        }
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param string $link
     * @param int $page
     * @throws Error404
     */
    protected function actionOneCategory(string $link, $page)
    {
        $this->mainPage = new View('/App/templates/category_page.phtml');
        $countArticle = Article::getCountArticleInCategory($link);
        $this->mainPage->pagination = new Pagination($countArticle, $page, Article::PER_PAGE);
        $this->mainPage->news = Article::findByCategory($link, is_null($page) ? 1 : $page, true);
        if (! $this->mainPage->news) {
            throw new Error404('Несуществующая страница');
        }
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }
}