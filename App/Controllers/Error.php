<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 22.10.2017
 * Time: 12:49
 */

namespace App\Controllers;

use App\View;

class Error extends \App\Controller
{
    protected $errorPage;

    public function __construct()
    {
    }

    protected function actionPage404()
    {
        parent::__construct();
        $this->errorPage = new View('/App/templates/layouts/errors/error404.phtml');
        View::display([$this->header, $this->errorPage, $this->sideBar, $this->footer]);
    }

    protected function actionTroubleDb()
    {
        $this->errorPage = new View('/App/templates/layouts/errors/error.phtml');
        View::display([$this->errorPage]);
    }
}