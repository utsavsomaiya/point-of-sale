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
						<span style="margin-right:80px;">Products</span>
						<a href="add_product.php">Add New Product</a>
					</h4>
					<table class="table">
						<form method="post">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Price</th>
									<th>Category</th>
									<th>Image</th>
									<th colspan='2'>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    try {
                                        $pdo =  new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
                                        $pdo->setAttribute(
                                            PDO::ATTR_ERRMODE,
                                            PDO::ERRMODE_EXCEPTION
                                        );
                                    } catch (PDOException $e) {
                                        die($e->getMessage());
                                    }
                                    $fetch = $pdo->prepare('select * from product');
                                    $fetch->execute();
                                    $result = $fetch->fetchAll();
                                    foreach ($result as $r) {
                                        if (!empty($r)) {
                                            ?>
								<tr>
									<td><?= $r['id']?></td>
									<td><?= $r['name'] ?></td>
									<td><?= "$".$r['price'] ?></td>
									<td><?= $r['category'] ?></td>
									<td><img src="<?= '/images/'.$r['image'] ?>"></td>
									<td><a href="../product/edit_product.php?id=<?= $r['id']?>" class="btn btn-dark btn-icon-text">Edit<i class="ti-file btn-icon-append"></i></a></td>
									<td><a href="javascript:alert(<?= $r['id']?>)" class="btn btn-outline-danger btn-fw">Delete</a>
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
