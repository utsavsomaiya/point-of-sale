<?php
session_start();
require '../layout/db_connect.php';
include '../layout/header.php';
if (isset($_POST['submit'])) {
    if (!empty($_FILES['image']['name']) && !empty($_POST['pname']) && !empty($_POST['price'] && !empty($_POST['category'])) && !empty($_POST['tax'])) {
        list($width, $height) = @getimagesize($_FILES['image']['name']);
        $target = "/admin/images/" . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $_SESSION['file_validation_error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            header('location:../product/add_product.php');
        } elseif (($_FILES["image"]["size"] > 2000000)) {
            $_SESSION['file_validation_error'] = "Image size exceeds 2MB";
            header('location:../product/add_product.php');
        } elseif ($width >= "300" || $height >= "200") {
            $_SESSION['file_validation_error'] = "Image dimension should be within 300X200";
            header('location:../product/add_product.php');
        } elseif (!is_numeric($_POST['price'])) {
            $_SESSION['file_validation_error'] = "Enter Only Numeric Value";
            header('location:../product/add_product.php');
        } else {
            $pname = $_POST['pname'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $tax = $_POST['tax'];
            $name = $_FILES['image']['name'];
            $fetch = $pdo->prepare("insert into product(name,price,category,tax,image) values(:pname,:price,:category,:tax,:name)");
            $fetch->bindParam(':pname', $pname);
            $fetch->bindParam(':price', $price);
            $fetch->bindParam(':category', $category);
            $fetch->bindParam(':tax', $tax);
            $fetch->bindParam(':name', $name);
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
            $_SESSION['name_validation_error'] = "Please enter data..";
            header('location:../product/add_product.php');
        }
        if (empty($_POST['price'])) {
            $_SESSION['price_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
        if (empty($_POST['category'])) {
            $_SESSION['category_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
        if (empty($_POST['tax'])) {
            $_SESSION['tax_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
        if (empty($_FILES['image']['name'])) {
            $_SESSION['file_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
    }
}
if (isset($_POST['pname'])) {
    $_SESSION['pname'] = $_POST['pname'];
}
if (isset($_POST['price'])) {
    $_SESSION['price'] = $_POST['price'];
}
if (isset($_POST['category'])) {
    $_SESSION['category'] = $_POST['category'];
}
if (isset($_POST['tax'])) {
    $_SESSION['tax'] = $_POST['tax'];
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
                                <input type="text" class="form-control" placeholder="Product Name" name="pname" <?php if (isset($_SESSION['pname'])) echo "value=\"" . $_SESSION['pname'] . "\""; ?> required>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['name_validation_error'])) {
                                        echo $_SESSION['name_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product Price</label>
                                <input type="number" class="form-control" placeholder="Product Price" name="price" <?php if (isset($_SESSION['price'])) echo "value=\"" . $_SESSION['price'] . "\""; ?> required>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['price_validation_error'])) {
                                        echo $_SESSION['price_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleSelectGender">Select Category</label>
                                <select class="form-control" name="category" required>
                                    <option value="">--Select Category--</option>
                                    <?php
                                    $fetch = $pdo->prepare("select * from category");
                                    $fetch->execute();
                                    $res = $fetch->fetchAll();
                                    foreach ($res as $category) {
                                    ?>
                                        <option value="<?= $category['id'] ?>" <?php
                                                                                if (isset($_SESSION['category'])) {
                                                                                    echo "selected='selected'";
                                                                                } ?>><?= $category['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['category_validation_error'])) {
                                        echo $_SESSION['category_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleSelectGender">Tax of the Product</label>
                                <select class="form-control" name="tax" required>
                                    <option value="">--Select Tax--</option>
                                    <option value="5">5%</option>
                                    <option value="10">10%</option>
                                    <option value="15">15%</option>
                                    <option value="20">20%</option>
                                    <option value="25">25%</option>
                                </select>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['tax_validation_error'])) {
                                        echo $_SESSION['tax_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Image</label>
                                <input type="file" class="form-control" accept="" name="image" required>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['file_validation_error'])) {
                                        echo $_SESSION['file_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit">Submit</button>
                            <a href="../product/show_product.php" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>