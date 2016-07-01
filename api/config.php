<?php
if (count(get_included_files()) == 1) {
    exit("Direct access not permitted.");
}

date_default_timezone_set('Asia/Dhaka');
define('SITEURL', 'http://localhost/blog/');
define('DBHOST', 'localhost');
define('DBNAME', 'blog');
define('DBUSER', 'root');
define('DBPASS', '');
