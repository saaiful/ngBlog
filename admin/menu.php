<?php require_once 'header.php';require_once 'nav.php';
auth();
if (isset($_POST['new'])) {
    $data = json_decode(file_get_contents("menu.json"), true);
    $data[] = ["id" => $data['last'] + 1, "name" => $_POST['name'], "link" => $_POST['link']];
    $data['last'] = $data['last'] + 1;
    file_put_contents("menu.json", json_encode($data));
    die();
}
if (isset($_POST['json'])) {
    $data = json_decode(file_get_contents("menu.json"), true);
    $json = json_decode($_POST['json'], true);
    $json["last"] = $data['last'];
    file_put_contents("menu.json", json_encode($json));
    die();
}
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12 white-bg">
			<div style="text-align:right;">
				<button type="button" onclick="newItem();" class="btn btn-raised btn-info">New Item</button>
			</div>
			<div class="dd">
				<ol class="dd-list">

				</ol>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal-menu">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Menu Item</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="text" class="form-control" id="name" placeholder="Name">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" id="link" placeholder="Link">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" onclick="newItemSave();" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="assets/js/admin.js"></script>
<?php require_once 'footer.php';?>