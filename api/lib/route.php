<?php
class Route
{
    public function __construct()
    {
        $this->get = [];
        $this->post = [];
    }

    public function get($name, $option)
    {
        $name = str_replace([':number', ':string'], ['([0-9]+)', "([a-zA-Z0-9_-]+)"], $name);
        $this->get[$name] = $option;
    }

    public function post($name, $option)
    {
        $name = str_replace([':number', ':string'], ['([0-9]+)', "([a-zA-Z0-9_-]+)"], $name);
        $this->post[$name] = $option;
    }

    public function run()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $query = str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
        $query = str_replace($query, "/", $_SERVER['REQUEST_URI']);
        $query = preg_replace("/(\?.*)/", "$2", $query);
        if (empty($query)) {
            $query = "/";
        }

        foreach ($this->{$method} as $key => $value) {
            // var_dump("/^" . substr(json_encode($key), 1, -1) . "$/");
            if (preg_match("/^" . substr(json_encode($key), 1, -1) . "$/", $query, $match)) {
                // var_dump($match);
                preg_match("/(.*)@(.*)/", $value, $action);
                // var_dump($action);
                $route = new $action[1]();
                $count = count($match);
                if ($count == 1) {
                    return $route->{$action[2]}();
                } elseif ($count == 2) {
                    return $route->{$action[2]}($match[1]);
                } elseif ($count == 3) {
                    return $route->{$action[2]}($match[1], $match[2]);
                }
            }
        }
        return http_response_code(404);

        /*if (array_key_exists($query, $this->{$method})) {
    preg_match("/(.*)@(.*)/", $this->{$method}[$query], $match);
    if (count($match) != 3) {
    throw new Exception("Invalid Parameter", 1);
    }
    if (class_exists($match[1])) {
    $route = new $match[1]();
    if (method_exists($route, $match[2])) {
    return $route->$match[2]();
    } else {
    throw new Exception("Method Not Exits", 1);
    }
    } else {
    throw new Exception("Class Not Exits", 1);
    }

    } else {
    return http_response_code(404);
    }*/
    }

}
