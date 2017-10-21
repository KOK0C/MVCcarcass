<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.10.2017
 * Time: 17:46
 */

namespace App\Controllers;

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

    public function actionIndex()
    {
        $this->mainPage->news = \App\Models\Article::findAll();
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    public function actionOnePage(int $id)
    {
        $this->mainPage = new View('/App/templates/one_article.phtml');
        $this->mainPage->article = \App\Models\Article::findById($id);
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    public function actionNews()
    {
        $this->mainPage->news = Article::findByCategory('news');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    public function actionOverviews()
    {
        $this->mainPage->news = Article::findByCategory('overviews');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    public function actionTechnologies()
    {
        $this->mainPage->news = Article::findByCategory('technologies');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    public function actionTuning()
    {
        $this->mainPage->news = Article::findByCategory('tuning');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }

    public function actionUseful()
    {
        $this->mainPage->news = Article::findByCategory('useful');
        View::display([$this->header, $this->mainPage, $this->sideBar, $this->footer]);
    }
}