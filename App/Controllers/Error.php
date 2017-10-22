<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 22.10.2017
 * Time: 12:49
 */

namespace App\Controllers;


use App\Controller;
use App\View;

class Error extends Controller
{
    protected $errorPage;

    public function __construct()
    {
        parent::__construct();
        $this->errorPage = new View('/App/templates/error404.phtml');
        View::display([$this->header, $this->errorPage, $this->sideBar, $this->footer]);
    }
}