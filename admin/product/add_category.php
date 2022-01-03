<?php
  session_start();
  include '../layout/header.php';
  if (isset($_POST['submit'])) {
      try {
          $pdo =  new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
          $pdo->setAttribute(
              PDO::ATTR_ERRMODE,
              PDO::ERRMODE_EXCEPTION
          );
      } catch (PDOException $e) {
          die($e->getMessage());
      }
      if (!empty($_POST['pname'])) {
          $pname = $_POST['pname'];
          $fetch = $pdo->prepare("insert into category(name) values('$pname')");
          $result = $fetch->execute();
          if (isset($result)) {
              $_SESSION['msg'] = "Add Successfully";
              header('location:../product/show_category.php');
          } else {
              echo 'No';
          }
      }
      if (empty($pname)) {
          $name_alert = "Please enter data..";
      }
  }
?>
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-md-6 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Add new Category</h4>
						<form class="forms-sample" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="exampleInputUsername1">Category Name</label>
								<input type="text" class="form-control" id="exampleInputUsername1"
									placeholder="Category Name" name="pname"
                                    <?php
                                    if (isset($_POST['pname'])) {
                                        echo "value=\"".$_POST['pname']."\"";
                                    }?>
                                required>
                                <label style="color:red;">
                                <?php
                                if (isset($name_alert)) {
                                    echo $name_alert;
                                }
                                ?>
                                </label>
							</div>
							<button type="submit" class="btn btn-primary me-2" name="submit">Submit</button>
							<button class="btn btn-light">Cancel</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include '../layout/footer.php'; ?>