<?php
    session_start();
    include '../layout/header.php';
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
                                    require '../layout/db_connect.php';
                                    $fetch = $pdo->prepare('select * from category');
                                    $fetch->execute();
                                    $result = $fetch->fetchAll();
                                    foreach ($result as $r) {
                                        if (!empty($r)) {
                                            ?>
								<tr>
									<td><?= $r['id']?></td>
									<td><?= $r['name'] ?></td>
									<td><a href="../category/edit_category.php?id=<?= $r['id']?>"><img
                                                src="/admin/image/1.png" /></a></a></td>
									<td><a href="javascript:alert_c(<?= $r['id']?>)"><i class="fa fa-trash-o" style="font-size:24px"></i></a>
									</td>
								</tr>
								<?php
                                        } else {
                                            echo "No Record Found..";
                                        }
                                    }
                                ?>
							</tbody>
						</form>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include '../layout/footer.php';
    unset($_SESSION['msg']);
?>