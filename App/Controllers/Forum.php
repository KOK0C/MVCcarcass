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
use IhorRadchenko\App\View;

class Forum extends Controller
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        $this->mainPage = new View('/App/templates/forum/main.phtml');
        $this->mainPage->forums = ForumSection::getMainForumSection();
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }
}