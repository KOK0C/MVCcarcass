<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 27.11.2017
 * Time: 17:54
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\Token;
use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car;
use IhorRadchenko\App\Models\Review;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

class Reviews extends Controller
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        if ($this->isAjax() && isset($_POST['mark'])) {
            $mark = ucwords(str_replace('-', ' ', $_POST['mark']));
            $data = Car::findCarsByBrand($mark);
            print json_encode($data, JSON_UNESCAPED_UNICODE);
            exit();
        } elseif ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('reviews', Review::findPerPage($_POST['page'], Review::PER_PAGE));
            exit();
        }
        $this->mainPage = new View('/App/templates/reviews.phtml');
        $this->mainPage->totalPage = ceil(Review::getCountReview() / Review::PER_PAGE);
        $this->mainPage->reviews = Review::findPerPage(1, Review::PER_PAGE);
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param $page
     * @param string $mark
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionMark($page, string $mark)
    {
        $mark = ucwords(str_replace('-', ' ', $mark));
        if (isset($_POST['page'])) {
            View::loadForAjax('reviews', Review::findReviewsByBrand($_POST['page'], $mark));
            exit();
        }
        $this->mainPage = new View('/App/templates/reviews.phtml');
        $this->mainPage->totalPage = ceil(Review::getCountReview($mark) / Review::PER_PAGE);
        $this->mainPage->cars = Car::findCarsByBrand($mark);
        if (! $this->mainPage->cars) {
            throw new Error404();
        }
        $this->header->page->title = "Отзывы об $mark";
        $this->mainPage->reviews = Review::findReviewsByBrand(1, $mark);
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @param $page
     * @param string $mark
     * @param string $model
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionModel($page, string $mark, string $model)
    {
        $mark = ucwords(str_replace('-', ' ', $mark));
        $model = mb_strtoupper(str_replace('-', ' ', $model));
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('reviews', Review::findReviewsByModel($_POST['page'], $mark, $model));
            exit();
        }
        $this->mainPage = new View('/App/templates/reviews.phtml');
        if (! Car::findCarByBrandAndModel($mark, $model)) {
            throw new Error404();
        }
        $this->mainPage->totalPage = ceil(Review::getCountReview($mark, $model) / Review::PER_PAGE);
        $this->header->page->title = "Отзывы об $mark $model";
        $this->mainPage->reviews = Review::findReviewsByModel(1, $mark, $model);
        $this->mainPage->cars = Car::findCarsByBrand($mark);
        $this->mainPage->brands = Brand::findAll(false, 'name');
        View::display($this->header, $this->mainPage, $this->sideBar, $this->footer);
    }

    /**
     * @throws Error404
     * @throws DbException
     */
    protected function actionCreate()
    {
        if (Session::has('user') && $this->isPost('add_review') && Token::check('token_add_review', $_POST['token_add_review'])) {
            $review = new Review();
            $validRules = [
                'text' => [
                    'required' => true,
                ]
            ];
            if (isset($_POST['f_name'])) {
                $validRules['f_name'] = [
                    'required' => true,
                    'minLength' => 2,
                    'maxLength' => 30,
                ];
                $user = User::findById(Session::get('user')->getId());
                if ($user->load($_POST, $validRules)) {
                    $user->save();
                    Session::set('user', User::findById($user->getId()));
                } else {
                    Session::set('add_review', 'fail');
                    Redirect::to();
                }
            }
            if ($review->load($_POST, $validRules)) {
                $review->save();
                Redirect::to();
            } else {
                Session::set('add_review', 'fail');
                Redirect::to();
            }
        }
        throw new Error404();
    }
}