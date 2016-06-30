<?php require_once 'header.php';?>
<?php
if (isset($_POST['signin'])) {
    $auth = new AuthController();
    $auth->signin($_POST['email'], $_POST['password']);
}
?>
<style type="text/css">
footer{ display: none; }
</style>
<div class="container">
	<div class="col-sm-4 col-sm-offset-4 white-bg">
		<div class="text-center">
			<img src="assets/img/logo.svg" alt="">
		</div>
		<form action="" method="POST" role="form">

			<div class="form-group">
				<input type="email" class="form-control" name="email" placeholder="Email">
			</div>

			<div class="form-group">
				<input type="password" class="form-control" name="password" placeholder="Password">
			</div>

			<div class="form-group">
				<button type="submit" name="signin" class="btn btn-raised btn-info btn-block">Signin</button>
			</div>

		</form>
	</div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
    var a = $(".white-bg").height();
    var b = $(window).height();
    c = ((b-a)/2)-90;
    $(".container").css('margin-top',c);
});
</script>

<?php require_once 'footer.php';?>