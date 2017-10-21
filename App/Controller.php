<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 18.10.2017
 * Time: 18:24
 */

namespace App;

abstract class Controller
{
    protected $header;
    protected $sideBar;
    protected $footer;

    public function __construct()
    {
        $this->header = new \App\View('/App/templates/layouts/header.phtml');
        $this->header->categories = \App\Models\Category::findAll();
        $this->sideBar = new \App\View('/App/templates/layouts/sidebar.phtml');
        $this->sideBar->brands = \App\Models\Brand::findAll();
        $this->footer = new \App\View('/App/templates/layouts/footer.phtml');
    }
}