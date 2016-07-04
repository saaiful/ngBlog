<?php
class HomeController extends Controller
{

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        $db = new DB();
        $db->table('posts')
           ->where("type", "=", 1)
           ->leftjoin('images', 'fimage', 'id')
           ->orderBy('posts.id', 'desc')
           ->pagination(10);
        return $this->json($db->get(['posts.title', 'images.uri', 'posts.slug']));
    }

    public function post($slug)
    {
        $db = new DB();
        $db->table('posts')->where("slug", "=", $slug);
        $db->leftjoin("users", 'user_id', 'id');
        $db->leftjoin("images", 'fimage', 'id');
        $result = $db->first(['posts.type', 'posts.title', 'posts.details', 'posts.keywords', 'users.name', 'images.uri']);
        if (!empty($result['uri'])) {
            $result['uri'] = "assets/images/" . $result['uri'];
        }
        return (!$result) ? $this->json("404", 404) : $this->json($result);
    }

    public function github($name)
    {
        $time1 = strtotime(date("Y-m-d H:i:s", filemtime("github.json")));
        $time2 = strtotime(date("Y-m-d H:i:s"));
        $diff = ($time2 - $time1) / 60;
        header('Content-Type: application/json');
        if (file_exists("github.json") && $diff < 60) {
            echo file_get_contents("github.json");
        } else {
            $url = "https://api.github.com/users/$name/repos";
            $ch = new Curl();
            $data = $ch->get($url);
            file_put_contents("github.json", $data);
            echo $data;
        }
        return;
    }

}
