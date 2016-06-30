<?php
require_once '../api/autoload.php';
auth();
if (isset($_GET['id'])) {
    $db = new DB();
    $db->table('posts')->where("id", "=", $_GET['id'])->delete();
    redirect('index.php');
}
