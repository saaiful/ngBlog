<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>ngBlog Installer</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<h2 class="text-center">ngBlog Installer</h2>
					<?php
require_once 'api/config.php';
require_once 'api/helper.php';
$msg = '';
$style = '';
if (isset($_POST['install'])) {
    try {
        $db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=UTF8", DBUSER, DBPASS);
        $query = $db->prepare("SELECT * FROM users");
        $query->execute();
        if (!$query->fetch()) {
            $query = $db->prepare(file_get_contents("database.sql"));
            $query->execute();
            $query = $db->prepare("INSERT INTO users (name,email,password,role) VALUES(?,?,?,?)");
            $query->execute([$_POST['name'], $_POST['email'], pHash($_POST['password']), 2]);
            $html = file_get_contents("index.html");
            file_put_contents("index.html", str_replace("BLOGNAME", $_POST['blog'], $html));
            echo '<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		You are ready to go!
	</div>';
            die();
        }
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}
if (isset($_GET)) {
    try {
        $db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=UTF8", DBUSER, DBPASS);
        $query = $db->prepare("SELECT * FROM users");
        $query->execute();

        if ($query->fetch()) {
            $msg = '<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			ngBlog Already Installed!
	</div>';
            $style = "display:none;";
        }

    } catch (PDOException $e) {
        $msg = '<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		' . $e->getMessage() . '
	</div>';
        $style = "display:none;";
    }
    echo $msg;
}
?>
				<form action="" method="POST" style="<?php echo $style;?>">
					<div class="form-group">
						<label for="">Blog Name</label>
						<input type="text" class="form-control" name="blog" required>
					</div>

					<div class="form-group">
						<label for="">Full Name</label>
						<input type="text" class="form-control" name="name" required>
					</div>

					<div class="form-group">
						<label for="">Email Address</label>
						<input type="email" class="form-control" name="email" required>
					</div>

					<div class="form-group">
						<label for="">Password</label>
						<input type="password" class="form-control" name="password" required>
					</div>

					<div class="form-group">
						<button type="submit" name="install" class="btn btn-default">Start Blogging</button>
					</div>

				</form>

				</div>
			</div>
		</div>

		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
 		<script src="Hello World"></script>
	</body>
</html>
