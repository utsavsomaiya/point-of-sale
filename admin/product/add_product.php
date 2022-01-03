<?php
  session_start();
  require '../layout/db_connect.php';
  include '../layout/header.php';
  if (isset($_POST['submit'])) {
      if (!empty($_FILES['image']['name']) && !empty($_POST['pname']) && !empty($_POST['price'])) {
          list($width, $height) = @getimagesize($_FILES['image']['name']);
          $target = "images/".basename($_FILES['image']['name']);
          $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
          if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif") {
              $file_alert = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          } elseif (($_FILES["image"]["size"] > 2000000)) {
              $file_alert = "Image size exceeds 2MB";
          } elseif ($width >= "300" || $height >= "200") {
              $file_alert = "Image dimension should be within 300X200";
          } elseif (!is_numeric($_POST['price'])) {
              $price_alert = "Enter Only Numeric Value";
          } else {
              $pname = $_POST['pname'];
              $price = $_POST['price'];
              $category =$_POST['category'];
              $name = $_FILES['image']['name'];
              $fetch = $pdo->prepare("insert into product(name,price,category,image) values('$pname','$price','$category','$name')");
              $result = $fetch->execute();
              if (isset($result)) {
                  $_SESSION['msg'] = "Add Successfully";
                  header('location:../product/show_product.php');
              } else {
                  $_SESSION['msg'] = "Not Successfully";
              }
          }
      } else {
          if (empty($pname)) {
              $name_alert = "Please enter data..";
          }
          if (empty($price)) {
              $price_alert = "Please enter data";
          }
          if (empty($_FILES['image']['name'])) {
              $file_alert = "Please enter data";
          }
      }
  }
  if (isset($_POST['cancel'])) {
      header('location:/admin/product/show_product.php');
  }
?>
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-md-6 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Add new Product</h4>
						<form class="forms-sample" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="exampleInputUsername1">Product Name</label>
								<input type="text" class="form-control" id="exampleInputUsername1"
									placeholder="Product Name" name="pname"
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
							<div class="form-group">
								<label for="exampleInputEmail1">Product Price</label>
								<input type="number" class="form-control" id="exampleInputEmail1"
									placeholder="Product Price" name="price"
                                    <?php
                                    if (isset($_POST['price'])) {
                                        echo "value=\"".$_POST['price']."\"";
                                    }?>
                                required>
                                <label style="color:red;">
                                <?php
                                if (isset($price_alert)) {
                                    echo $price_alert;
                                }
                                ?>
                                </label>
							</div>
                            <div class="form-group">
                                <label for="exampleSelectGender">Select Category</label>
                                <select class="form-control" id="exampleSelectGender" name="category" required>
                                    <option value="">--Select Category--</option>
                                    <?php
                                        $fetch = $pdo->prepare("select * from category");
                                        $fetch->execute();
                                        $res = $fetch->fetchAll();
                                        foreach ($res as $r1) {
                                            ?>
                                    <option  value="<?= $r1['name'] ?>"><?= $r1['name']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
							<div class="form-group">
								<label for="exampleInputPassword1">Image</label>
								<input type="file" class="form-control" accept="" name="image" required>
                                <label style="color:red;">
                                <?php
                                if (isset($file_alert)) {
                                    echo $file_alert;
                                }
                                ?>
                                </label>
							</div>
							<button type="submit" class="btn btn-primary me-2" name="submit">Submit</button>
							<button class="btn btn-light" name="cancel">Cancel</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>

</script>
<?php include '../layout/footer.php'; ?>