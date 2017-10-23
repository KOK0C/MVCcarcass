<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.10.2017
 * Time: 17:46
 */

namespace App\Controllers;

use App\Exceptions\Error404;
use App\Models\Article;
use App\View;

class Main extends \App\Controller
{
    protected $mainPage;

    public function __construct()
    {
        parent::__construct();
        $this->mainPage = new View('/App/templates/main_page.phtml');
    }

    protected function actionIndex()
    {
        $this->mainPage->news = \App\Models\Article::findAll();
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    /**
     * @param int $id
     * @throws Error404
     */
    protected function actionOnePage(int $id)
    {
        $this->mainPage = new View('/App/templates/one_article.phtml');
        $this->mainPage->article = \App\Models\Article::findById($id);
        if (empty($this->mainPage->article)) {
            throw new Error404();
        }
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    protected function actionNews()
    {
        $this->mainPage->news = Article::findByCategory('news');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    protected function actionOverviews()
    {
        $this->mainPage->news = Article::findByCategory('overviews');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    protected function actionTechnologies()
    {
        $this->mainPage->news = Article::findByCategory('technologies');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    protected function actionTuning()
    {
        $this->mainPage->news = Article::findByCategory('tuning');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    protected function actionUseful()
    {
        $this->mainPage->news = Article::findByCategory('useful');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }
}