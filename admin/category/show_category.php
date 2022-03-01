<?php
    session_start();
    include '../layout/header.php';
    require '../layout/db_connect.php';
    $fetch = $pdo->prepare('SELECT * FROM `category`');
    $fetch->execute();
    $result = $fetch->fetchAll();
?>
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-lg-6 grid-margin stretch-card">
				<div class="card-body">
					<h4 class="card-title">
						<span style="margin-right:80px;">Products Category</span>
						<a href="add_category.php">Add New Category</a>
					</h4>
					<?php
                        if (sizeof($result)>0) {
                            ?>
					<table class="table">
						<form method="post">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th colspan='2'>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    foreach ($result as $category) {
                                        ?>
								<tr>
									<td><?= $category['id']?></td>
									<td><?= $category['name'] ?></td>
									<td><a href="../category/edit_category.php?id=<?= $category['id']?>"><img
                                                src="/admin/image/edit-icon.png" /></a></a></td>
									<td><a href="javascript:deleteCategory(<?= $category['id']?>)"><i class="fa fa-trash-o" style="font-size:24px"></i></a>
									</td>
								</tr>
								<?php
                                    } ?>
							</tbody>
						</form>
					</table>
					<?php
                        } else {
                            echo "No Record Found..";
                        }
                    ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include '../layout/footer.php';
    unset($_SESSION['msg']);
?>