<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 20.11.2017
 * Time: 19:21
 */

namespace IhorRadchenko\App\Controllers;

use IhorRadchenko\App\Components\Cookie;
use IhorRadchenko\App\Components\Redirect;
use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\Token;
use IhorRadchenko\App\Controller;
use IhorRadchenko\App\DataBase;
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
                    'unique' => 'users',
                    'maxLength' => 100
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
            Session::set('signup', 'fail');
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

                if (isset($_POST['remember_me']) && $_POST['remember_me'] === 'on') {
                    $hash = hash('sha256', uniqid());
                    $hashCheck = DataBase::getInstance()->get('user_sessions', 'user_id', Session::get('user')->getId());
                    if (! $hashCheck) {
                        DataBase::getInstance()->insert(
                            'user_sessions',
                            ['user_id' => Session::get('user')->getId(), 'hash_user' => $hash]
                        );
                    } else {
                        $hash = $hashCheck->hash_user;
                    }
                    Cookie::set('user', $hash);
                }

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
            DataBase::getInstance()->delete('user_sessions', 'user_id', Session::get('user')->getId());
            Cookie::delete('user');
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user']) && Token::check('update_user_token', $_POST['update_user_token'])) {
                $user = \IhorRadchenko\App\Models\User::findById(Session::get('user')->getId());
                $validRules = [
                    'f_name' => [
                        'required' => true,
                        'minLength' => 2,
                        'maxLength' => 30,
                    ],
                    'l_name' => [
                        'required' => true,
                        'minLength' => 2,
                        'maxLength' => 40,
                    ],
                    'email' => [
                        'email' => true,
                        'maxLength' => 100
                    ],
                    'phone_number' => [
                        'required' => true,
                        'length' => 13,
                        'phone' => true,
                    ]
                ];
                if ($user->email !== $_POST['email']) {
                    $validRules['email']['unique'] = 'users';
                }
                if ($user->phone_number !== $_POST['phone_number']) {
                    $validRules['phone_number']['unique'] = 'users';
                }
                if ($user->load($_POST, $validRules)) {
                    $user->save();
                } else {
                    Session::set('update_user', 'fail');
                }
            }
            $this->mainPage = new View('/App/templates/personal_area/profile.phtml');
            $this->mainPage->user = \IhorRadchenko\App\Models\User::findById(Session::get('user')->getId());
            Session::set('user', $this->mainPage->user);
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }

    protected function actionChangePassword()
    {
        if (Session::has('user')) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password']) && Token::check('change_password_token', $_POST['change_password_token'])) {
                $user = \IhorRadchenko\App\Models\User::findById(Session::get('user')->getId());
                if ($user->passwordVerify($_POST['oldPassword'])) {
                    $validRules = [
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
                    } else {
                        Session::set('change_pass', 'fail');
                    }
                } else {
                    Session::set('change_pass', 'fail');
                    Session::set('errors', ['old_pass' => json_encode(['Неверный пароль'], JSON_UNESCAPED_UNICODE)]);
                }
            }
            $this->mainPage = new View('/App/templates/personal_area/change_password.phtml');
            View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
        } else {
            throw new Error404();
        }
    }

    protected function buildSideBar()
    {
        $this->sideBar = new View('/App/templates/personal_area/sidebar.phtml');
    }
}