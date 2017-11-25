<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 20.11.2017
 * Time: 19:21
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\Token;
use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\View;

class User extends Controller
{
    private $mainPage;

    protected function actionSignUp()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup']) && Token::check('signup_token', $_POST['signup_token'])) {
            $user = new \IhorRadchenko\App\Models\User();
            $validRules = [
                'email' => [
                    'email' => true,
                    'unique' => 'users'
                ],
                'password' => [
                    'required' => true,
                    'minLength' => 6,
                    'alnum' => true
                ],
                'passwordAgain' => [
                    'match' => 'password'
                ]
            ];
            if ($user->load($_POST, $validRules)) {
                $user->passwordHash();
                $user->save();
                Session::set('login', 'success');
                Redirect::to();
            }
            Redirect::to();
        }
        throw new Error404();
    }

    protected function actionLogIn()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) && Token::check('login_token', $_POST['login_token'])) {
            $user = \IhorRadchenko\App\Models\User::findByEmail($_POST['email']);
            if ($user && $user->passwordVerify($_POST['password'])) {
                Session::set('user', $user);
                Redirect::to('/user');
            }
            Session::set('login', 'fail');
            Redirect::to();
        }
        throw new Error404();
    }

    protected function actionLogOut()
    {
        if (Session::has('user')) {
            Session::delete('user');
            Redirect::to('/');
        }
        throw new Error404();
    }

    protected function actionPersonalArea()
    {
        if (Session::has('user')) {
            $this->mainPage = new View('/App/templates/personal_area/main.phtml');
            $this->mainPage->user = \IhorRadchenko\App\Models\User::findById(Session::get('user')->getId());
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }

    protected function actionProfile()
    {
        if (Session::has('user')) {
            $this->mainPage = new View('/App/templates/personal_area/profile.phtml');
            $this->mainPage->user = \IhorRadchenko\App\Models\User::findById(Session::get('user')->getId());
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }

    protected function actionChangePassword()
    {
        if (Session::has('user')) {
            View::display($this->header, $this->sideBar, $this->footer);
        } else {
            throw new Error404();
        }
    }

    protected function buildSideBar()
    {
        $this->sideBar = new View('/App/templates/personal_area/sidebar.phtml');
    }
}