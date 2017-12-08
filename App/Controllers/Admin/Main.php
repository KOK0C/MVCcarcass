<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 08.12.2017
 * Time: 18:58
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Controller;
use IhorRadchenko\App\View;

class Main extends Controller
{
    private $mainPage;

    protected function actionIndex()
    {
        View::display($this->header, $this->footer);
    }

    protected function buildHeader()
    {
        $this->header = new View('/App/templates/admin/layouts/header.phtml');
    }

    protected function buildFooter()
    {
        $this->footer = new View('/App/templates/admin/layouts/footer.phtml');
    }
}