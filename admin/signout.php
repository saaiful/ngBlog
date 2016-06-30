<?php
require_once '../api/autoload.php';
auth();
$auth = new Auth();
$auth->signout();
