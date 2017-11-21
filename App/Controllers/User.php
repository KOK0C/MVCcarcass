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
                'password_again' => [
                    'match' => 'password'
                ]
            ];
            if ($user->load($_POST, $validRules)) {
                $user->save();
                Redirect::to();
            }
            Session::set('signup_error', true);
            Redirect::to();
        }
        Redirect::to();
    }

    protected function actionLogIn()
    {

    }

    protected function actionLogOut()
    {

    }
}