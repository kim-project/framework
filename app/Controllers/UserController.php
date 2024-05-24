<?php

namespace app\Controllers;

use app\Models\User;
use Kim\Support\Database\DB;

class UserController extends Controller
{
    public function getLogin()
    {
        if (isset($_COOKIE['login']) && isset($_COOKIE['id']) && $_COOKIE['login']) {
            return response()->redirect('/');
        }
        return response()->view('Login.php', []);
    }

    public function login()
    {
        $email = $_POST['email'];
        $user = User::first("WHERE email='$email'");
        if ($user) {
            if (password_verify($_POST['password'], $user['password'])) {
                setcookie('login', true, time() + 86400);
                setcookie('id', $user['id'], time() + 86400);
                return response()->json($_COOKIE);
            } else {
                return response()->view('Login.php', [
                    'error' => 'Wrong password'
                ]);
            }
        } else {
            return response()->view('Login.php', [
                'error' => 'User not found'
            ]);
        }
    }

    public function logout()
    {
        if (isset($_COOKIE['login']) && isset($_COOKIE['id']) && $_COOKIE['login']) {
            setcookie('login', false, time() - 3600);
            setcookie('id', '', time() - 3600);
            return response()->redirect('/login');
        } else {
            return response()->redirect('/login');
        }
    }

    public function getSignup()
    {
        if (isset($_COOKIE['login']) && isset($_COOKIE['id']) && $_COOKIE['login']) {
            return response()->redirect('/');
        }
        return response()->view('Signup.php', []);
    }

    public function signup()
    {
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return response()->view('Signup.php', [
                    'error' => 'Invalid Email'
                ]);
            }

            $email = $_POST['email'];
            $user = User::first("WHERE email='$email'");
            if ($user) {
                return response()->view('Signup.php', [
                    'error' => 'Email already used'
                ]);
            }

            $name = $_POST['name'];
            $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
            DB::sql("INSERT INTO user (`name`, `email`, `password`) VALUES ('$name', '$email', '$pass')");
            return response()->redirect('/login');
        } else {
            return response()->view('Signup.php', [
                'error' => 'All fields required'
            ]);
        }
    }

    public function me()
    {
        if (isset($_COOKIE['login']) && isset($_COOKIE['id']) && $_COOKIE['login']) {
            $user = User::find($_COOKIE['id']);
            return response()->json($user);
        } else {
            return response(403, 'User not logged in');
        }
    }
}
