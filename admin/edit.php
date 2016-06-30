<?php require_once 'header.php';
require_once 'nav.php';
auth();
$data = new DB();
$data->table('posts');
$msg = '';
if (isset($_POST['id'])) {
    $data = new DB();
    $data->table('posts');
    $details = preg_replace("/<script .*([a-z0-9]{20})\.js.*<\/script>/", '<gist id="$1"></gist>', $_POST['details']);
    // $data->query("UPDATE posts SET title=? , details=? , slug=? , keywords=?, fimage=? WHERE id=?", [$_POST['title'], $details, $_POST['slug'], $_POST['keywords'], $_POST['fimage'], $_POST['id']]);
    $input = ["title" => $_POST['title'], "details" => $_POST['details'], "slug" => $_POST['slug'], "keywords" => $_POST['keywords'], "fimage" => $_POST['fimage']];
    $data->where("id", "=", $_POST['id']);
    $data->update($input);
    $msg = '<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<strong>Information Updated</strong>
</div>';
}

$data = $data->find(@$_GET['id']);
if (!$data) {
    require_once '404.php';
    require_once 'footer.php';
    die();
}

$db = new DB();
$db->table("images");
$images = $db->get();
$fimage_uri = array_search($data['fimage'], array_column($images, 'id'));
if ($data['fimage'] == 0) {
    $fimage_uri = '';
}
?>

<div class="container">
	<div class="white-bg">
		<div class="row">
		<div class="col-sm-12">
			<?php echo $msg;?>
		</div>
			<form action="" method="POST" id="form" role="form">
				<input type="hidden" name="id" value="<?php echo $data['id'];?>">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="">Post / Page Title</label>
						<input type="text" class="form-control" name="title" value="<?php echo $data['title'];?>" placeholder="Input field">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label for="">Slug</label>
						<input type="text" class="form-control" name="slug" value="<?php echo $data['slug'];?>" placeholder="Input field">
					</div>
				</div>

				<input type="hidden" value="0" id="fimage" name="fimage" class="form-control" value="">


				<div class="col-sm-6">
					<div class="form-group">
						<label for="">Featured Image</label>
						<button type="button" class="btn btn-raised btn-info" onclick="$('#image-select').modal('show');">Change Image</button>
						<!-- <input type="text" name="fimage" class="form-control" value="<?php echo $data['fimage'];?>"> -->
					</div>
				</div>

				<div class="col-sm-12">
					<img style="width: 100%;max-height:300px;" class="img-responsive img-thumbnail" id="dis-image" src="../assets/images/<?php echo @$images[$fimage_uri]['uri'];?>" alt="">
				</div>

				<div class="col-sm-12">
					<div class="form-group">
						<div id="txtEditor"></div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="form-group">
						<label for="">Keywords</label>
						<input type="text" class="form-control" name="keywords" value="<?php echo $data['keywords'];?>" placeholder="Input field">
					</div>
				</div>

				<textarea name="details" id="details" cols="30" rows="10" style="display:none;"></textarea>

				<div class="col-sm-12">
					<button type="button" onclick="saveContent()" class="btn btn-raised btn-info">Update Information</button>
				</div>


			</form>

		</div>
	</div>
</div>

<div class="modal fade" id="image-select">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Image Select</h4>
			</div>
			<div class="modal-body">
			<?php foreach ($images as $key => $image) {
    echo '<img class="img-thumbnail simage" data-id="' . $image['id'] . '" data-uri="' . $image['uri'] . '" src="../assets/images/sm_' . $image['uri'] . '" alt="">';
}
?>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
				$( document ).ready(function() {
					$("#txtEditor").Editor();
					$("#txtEditor").Editor("setText",<?php echo json_encode($data['details']);?>);
				});

				function saveContent() {
					$("#details").val($("#txtEditor").Editor('getText'));
					$("#form").submit();
				}

				$(".simage").on('click',function(){
					$("#fimage").val($(this).data('id'));
					$('#image-select').modal('hide');
					$('#dis-image').attr('src','../assets/images/'+$(this).data('uri'));
				});
			</script>
<?php require_once 'footer.php';
