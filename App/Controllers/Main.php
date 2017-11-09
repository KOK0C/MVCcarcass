<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.10.2017
 * Time: 17:46
 */

namespace App\Controllers;

use App\Components\Pagination;
use App\Exceptions\Error404;
use App\Models\Article;
use App\Models\Page;
use App\Models\Photo;
use App\View;

class Main extends \App\Controller
{
    /**
     * Шаблон страницы
     * @var View
     */
    protected $mainPage;

    protected function actionIndex()
    {
        $this->mainPage = new View('/App/templates/main_page.phtml');
        $this->mainPage->news = Article::findLastArticle();
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param string $link
     * @param int $id
     * @throws Error404
     */
    protected function actionOneArticle(string $link, int $id)
    {
        $this->mainPage = new View('/App/templates/one_article.phtml');
        $this->mainPage->article = Article::findOneArticle($link, $id);
        if (empty($this->mainPage->article)) {
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
        if (empty($this->mainPage->news)) {
            throw new Error404('Несуществующая категория');
        }
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }
}