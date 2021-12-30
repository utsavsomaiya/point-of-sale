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
					<?php
                        if (isset($_SESSION['msg'])) {
                            echo $_SESSION['msg'];
                        }
                    ?>
					<table class="table">
						<form method="post">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Price</th>
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
									<td><?= $r['price'] ?></td>
									<td><img src="<?= '/images/'.$r['image'] ?>"></td>
									<td><button type="submit" name="edit" class="btn btn-primary me-2">Edit</button>
									</td>
									<td><button type="submit" name="delete" class="btn btn-primary me-2">Delete</button>
									</td>
								</tr>
								<?php
                                        } else {
                                            echo "No Record Found..";
                                        }
                                    }
                                ?>
								<?php
                                    if (isset($_POST['edit'])) {
                                        header('location:../product/edit_product.php');
                                    }
                                    if (isset($_POST['delete'])) {
                                        header('location:../product/delete_product.php');
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
<?php include '../layout/footer.php'; ?>
