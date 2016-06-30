<?php

class AuthController
{

    public function __construct()
    {
        @session_start();
        $this->db = new DB();
        $this->db->table('users');
    }

    public function check()
    {
        if (isset($_SESSION['auth'])) {
            return true;
        } else {
            return false;
        }
    }

    public function signin($email, $password)
    {
        $result = $this->db->where('email', "=", $email)->where('password', "=", pHash($password))->first();
        if ($result) {
            $_SESSION['auth'] = md5(rand(10, 99) . rand(10, 99) . rand(10, 99));
            $_SESSION['user'] = $result;
            return redirect("index.php");
        } else {
            return redirect("signin.php");
        }
    }

    public function signout()
    {
        @session_destroy();
        return redirect("signin.php");
    }
}
