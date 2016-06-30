<?php require_once 'header.php';
require_once 'nav.php';
auth();
$data = new DB();
$posts = $data->table('posts')->where("type", "=", 1)->orderBy('id')->get();
$data = new DB();
$pages = $data->table('posts')->where("type", "=", 2)->orderBy('id')->get();
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading">All Posts</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-stiped">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Created At</th>
									<th>Updated At</th>
									<th class="text-right">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($posts as $key => $post) {?>
								<tr>
									<td><?php echo $post['id'];?></td>
									<td><?php echo $post['title'];?></td>
									<td><?php echo $post['created_at'];?></td>
									<td><?php echo $post['updated_at'];?></td>
									<td>
										<div class="btn-group pull-right">
											<a href="edit.php?id=<?php echo $post['id'];?>" target="_blink" class="btn btn-xs btn-raised btn-info">Edit</a>
											<a href="../#/<?php echo $post['slug'];?>" target="_blink" class="btn btn-xs btn-raised btn-success">View</a>
											<button type="button" onclick="deletePost(<?php echo $post['id'];?>);" class="btn btn-xs btn-raised btn-danger">Delete</button>
										</div>
									</td>
								</tr>
								<?php }
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading">All Pages</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-stiped">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Created At</th>
									<th>Updated At</th>
									<th class="text-right">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($pages as $key => $page) {?>
								<tr>
									<td><?php echo $page['id'];?></td>
									<td><?php echo $page['title'];?></td>
									<td><?php echo $page['created_at'];?></td>
									<td><?php echo $page['updated_at'];?></td>
									<td>
										<div class="btn-group pull-right">
											<a href="edit.php?id=<?php echo $page['id'];?>" class="btn btn-xs btn-raised btn-info">Edit</a>
											<a href="../#/<?php echo $page['slug'];?>" target="_blink" class="btn btn-xs btn-raised btn-success">View</a>
											<button type="button" onclick="deletePost(<?php echo $page['id'];?>);" class="btn btn-xs btn-raised btn-danger">Delete</button>
										</div>
									</td>
								</tr>
								<?php }
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php require_once 'footer.php';
