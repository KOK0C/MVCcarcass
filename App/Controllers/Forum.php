<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 02.12.2017
 * Time: 19:56
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Models\ForumSection;
use IhorRadchenko\App\Models\ForumTheme;
use IhorRadchenko\App\View;

class Forum extends Controller
{
    private $mainPage;
    private $leftSideBar;

    /**
     * Forum constructor.
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public function __construct()
    {
        $this->buildLeftSideBar();
        parent::__construct();
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        $this->mainPage = new View('/App/templates/forum/main.phtml');
        $this->mainPage->forums = ForumSection::getMainForumSection();
        View::display($this->header, $this->leftSideBar, $this->mainPage, $this->sideBar, $this->footer);
    }

    protected function actionSection(string $parent)
    {

    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    private function buildLeftSideBar()
    {
        $this->leftSideBar = new View('/App/templates/forum/forumSideBar.phtml');
        $this->leftSideBar->forums = ForumTheme::get5LastTheme();
    }
}