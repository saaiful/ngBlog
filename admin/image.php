<?php require_once 'header.php';require_once 'nav.php';?>
<?php
$db = new DB();
$images = $db->table('images')->get();
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12 white-bg">
			<div class="form-group">
				<!-- <label class="control-label">Select File</label> -->
				<input id="input-image" name="image[]" multiple type="file" class="file" placeholder="Select Image">
			</div>
		</div>

		<div class="col-sm-12 white-bg" style="margin-top:20px;">
			<?php foreach ($images as $key => $image) {?>
			<a onclick='imgDetail(<?php echo $image['id'];?>,"<?php echo $image['uri'];?>")' href="javascript:{};"><img src="../assets/images/sm_<?php echo $image['uri'];?>" class="img-thumbnail" alt="" style="height:200px; width:200px;"></a>
			<?php }
?>
		</div>
	</div>


	<div class="modal fade" id="image-info">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Information</h4>
				</div>
				<div class="modal-body">
					<input type="text" class="form-control" id="uri">
					<input type="text" class="form-control" id="id">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$("#input-image").fileinput({
			uploadUrl: "upload.php", // server upload action
			uploadAsync: false,
			showUpload:true,
			showUploadedThumbs:true,
			maxFileCount: 10,
			dropZoneEnabled: true
		});

		function imgDetail(id,uri) {
			$("#image-info").modal("show");
			$("#id").val(id);
			$("#uri").val(uri);
		}
	</script>
</div>
<?php require_once 'footer.php';?>
