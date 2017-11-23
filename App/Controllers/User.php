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
use IhorRadchenko\App\Controller;
use IhorRadchenko\App\Exceptions\Error404;

class User extends Controller
{
    protected function actionSignUp()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
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
                Redirect::to('/login');
            }
            Redirect::to();
        }
        throw new Error404();
    }

    protected function actionLogIn()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $user = \IhorRadchenko\App\Models\User::findByEmail($_POST['email']);
            if ($user && $user->passwordVerify($_POST['password'])) {
                Session::set('user', $user);
                Redirect::to();
            }
            Session::set('login', 'fail');
        }
        Redirect::to();
    }

    protected function actionLogOut()
    {
        if (Session::has('user')) {
            Session::delete('user');
        }
        Redirect::to();
    }
}