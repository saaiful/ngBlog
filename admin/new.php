<?php
require_once 'header.php';
require_once 'nav.php';
auth();
$msg = '';
$type = (@$_GET['type'] == 1) ? 'Post' : 'Page';
if (isset($_POST['title'])) {
    $data = new DB();
    $data->table('posts');
    $input = $_POST;
    $input['fimage'] = (!$input['fimage']) ? 0 : $input['fimage'];
    $input['user_id'] = $_SESSION['user']['id'];
    $input['type'] = $_GET['type'];
    $input['details'] = preg_replace("/<script .*([a-z0-9]{20})\.js.*<\/script>/", '<gist id="$1"></gist>', $_POST['details']);
    $id = $data->insert($input);
    if (is_string($id)) {
        echo '<script>window.location = "edit.php?id=' . $id . '";</script>';
        die();
    } else {
        $msg = '<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<strong>Something went wrong</strong>
</div>';
    }

}
$data = new DB();
$data->table('category');
$category = $data->get();
$data = new DB();
$data->table("images");
$images = $data->get();
?>

<div class="container">
	<div class="white-bg">
		<div class="row">
		<div class="col-sm-12">
			<?php echo $msg;?>
		</div>
			<form action="" method="POST" id="form" role="form">
				<div class="col-sm-12">
					<div class="form-group">
						<label for=""><?php echo $type;?> Title</label>
						<input type="text" class="form-control" name="title" id="title" value="<?php echo @$_POST['title'];?>" placeholder="Input field">
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label for="">Slug</label>
						<input type="text" class="form-control" name="slug" id="slug" value="<?php echo @$_POST['slug'];?>" placeholder="Input field">
					</div>
				</div>
				<input type="hidden" id="fimage" name="fimage" class="form-control" value="<?php echo @$_POST['fimage'];?>">


				<div class="col-sm-4">
					<div class="form-group">
						<label for="">Category</label>
						<select name="category" class="form-control">
							<option value="0"></option>
							<?php foreach ($category as $key => $value) {
    echo "<option value=\"$key\">{$value['name']}</option>";
}
?>
						</select>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label for="">Featured Image</label>
						<button type="button" class="btn btn-raised btn-info" onclick="$('#image-select').modal('show');">Select Image</button>
					</div>
				</div>

				<div class="col-sm-12">
					<img style="width: 100%;max-height:300px;" class="img-responsive img-thumbnail" id="dis-image" src="" alt="">
				</div>

				<div class="col-sm-12">
					<div class="form-group">
						<div id="txtEditor"></div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="form-group">
						<label for="">Keywords</label>
						<input type="text" class="form-control" name="keywords" value="" placeholder="Input field">
					</div>
				</div>

				<textarea name="details" id="details" cols="30" rows="10" style="display:none;"></textarea>

				<div class="col-sm-12">
					<button type="button" onclick="saveContent()" class="btn btn-raised btn-info">Save <?php echo $type;?></button>
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
	$("#txtEditor").Editor('setText',<?php echo json_encode(@$_POST['details']);?>);
	$("#title").on('keyup keydown',slugMake);
});

function saveContent() {
	$("#details").val($("#txtEditor").Editor('getText'));
	$("#form").submit();
}

function slugMake(){
	var text = $("#title").val();
	text = text.toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, '');
	$("#slug").val(text);
}

$(".simage").on('click',function(){
	$("#fimage").val($(this).data('id'));
	$('#image-select').modal('hide');
	$('#dis-image').attr('src','../assets/images/'+$(this).data('uri'));
});
</script>
<?php require_once 'footer.php';
