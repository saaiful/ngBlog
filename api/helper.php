<?php
function auth()
{
    $auth = new AuthController();
    if (!$auth->check()) {
        redirect("signin.php");
    }
}

function pHash($text)
{
    $text1 = strrev($text);
    return md5($text1) . md5($text);
}

function redirect($uri)
{
    try {
        header("Location: $uri");
    } catch (Exception $e) {
        echo '<script>window.location = "' . $uri . '";</script>';
        die();
    }
}

function api($data, $code = 200)
{
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}
