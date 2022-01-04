<?php
  session_start();
  require '../layout/db_connect.php';
  include '../layout/header.php';
    //print_r($_SESSION);
    if (isset($_POST['submit'])) {
        if (!empty($_FILES['image']['name']) && !empty($_POST['pname']) && !empty($_POST['price'] && !empty($_POST['category']))) {
            list($width, $height) = @getimagesize($_FILES['image']['name']);
            $target = "/admin/images/".basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif") {
                $_SESSION['msg_alert3'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                header('location:../product/add_product.php');
            } elseif (($_FILES["image"]["size"] > 2000000)) {
                $_SESSION['msg_alert3'] = "Image size exceeds 2MB";
                header('location:../product/add_product.php');
            } elseif ($width >= "300" || $height >= "200") {
                $_SESSION['msg_alert3'] = "Image dimension should be within 300X200";
                header('location:../product/add_product.php');
            } elseif (!is_numeric($_POST['price'])) {
                $_SESSION['msg_alert2'] = "Enter Only Numeric Value";
                header('location:../product/add_product.php');
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
                    header('location:../product/add_product.php');
                }
            }
        } else {
            if (empty($_POST['pname'])) {
                $_SESSION['msg_alert1'] = "Please enter data..";
                header('location:../product/add_product.php');
            }
            if (empty($_POST['price'])) {
                $_SESSION['msg_alert2'] = "Please enter data";
                header('location:../product/add_product.php');
            }
            if (empty($_POST['category'])) {
                $_SESSION['msg_alert4'] = "Please enter data";
                header('location:../product/add_product.php');
            }
            if (empty($_FILES['image']['name'])) {
                $_SESSION['msg_alert3'] = "Please enter data";
                header('location:../product/add_product.php');
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
                                        $_SESSION['pname']= $_POST['pname'];
                                    }
                                    if (isset($_SESSION['pname'])) {
                                        echo "value=\"".$_SESSION['pname']."\"";
                                    }?>
                                >
                                <label style="color:red;">
                                <?php
                                if (isset($_SESSION['msg_alert1'])) {
                                    echo $_SESSION['msg_alert1'];
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
                                        $_SESSION['price']= $_POST['price'];
                                    }
                                    if (isset($_SESSION['price'])) {
                                        echo "value=\"".$_SESSION['price']."\"";
                                    }
                                    ?>
                                >
                                <label style="color:red;">
                                <?php
                                if (isset($_SESSION['msg_alert2'])) {
                                    echo $_SESSION['msg_alert2'];
                                }
                                ?>
                                </label>
							</div>
                            <div class="form-group">
                                <label for="exampleSelectGender">Select Category</label>
                                <select class="form-control" id="exampleSelectGender" name="category" >
                                    <option value="">--Select Category--</option>
                                    <?php
                                        $fetch = $pdo->prepare("select * from category");
                                        $fetch->execute();
                                        $res = $fetch->fetchAll();
                                        foreach ($res as $r1) {
                                            ?>
                                    <option  value="<?= $r1['id'] ?>"
                                    <?php
                                    if (isset($_POST['category'])) {
                                        $_SESSION['category']= $_POST['category'];
                                    }
                                            if (isset($_SESSION['category'])) {
                                                echo "selected='selected'";
                                            } ?>
                                    ><?= $r1['name']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <label style="color:red;">
                                <?php
                                if (isset($_SESSION['msg_alert4'])) {
                                    echo $_SESSION['msg_alert4'];
                                }
                                ?>
                                </label>
                            </div>
							<div class="form-group">
								<label for="exampleInputPassword1">Image</label>
								<input type="file" class="form-control" accept="" name="image" >
                                <label style="color:red;">
                                <?php
                                if (isset($_SESSION['msg_alert3'])) {
                                    echo $_SESSION['msg_alert3'];
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
<?php include '../layout/footer.php'; ?>
<?php
    if (isset($_SESSION['pname'])) {
        unset($_SESSION['pname']);
    }
?>