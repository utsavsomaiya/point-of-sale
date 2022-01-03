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
									<td><a href="../category/edit_category.php?id=<?= $r['id']?>" class="btn btn-dark btn-icon-text">Edit<i class="ti-file btn-icon-append"></i></a></td>
									<td><a href="javascript:alert_c(<?= $r['id']?>)" class="btn btn-outline-danger btn-fw">Delete</a>
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
<?php
     if (isset($_SESSION['msg'])) {
         ?>
        <div id="snackbar"> <?php echo $_SESSION['msg']; ?> </div>
<?php
     }
?>
<?php include '../layout/footer.php';
    unset($_SESSION['msg']);
?>