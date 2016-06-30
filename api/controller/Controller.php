<?php

class Controller
{
    public function json($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        return;
    }

    public function redirect($uri)
    {
        try {
            header("Location: $uri");
        } catch (Exception $e) {
            echo '<script>window.location = "' . $uri . '";</script>';
            return;
        }
    }
}
