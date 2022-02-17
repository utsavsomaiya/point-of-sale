<?php
session_start();
require '../layout/db_connect.php';
$name = [];
$fetch = $pdo->prepare('SELECT `name` FROM `product`');
$fetch->execute();
$result = $fetch->fetchAll();
$i = 0;
if (isset($_POST['submit'])) {
    if (!empty($_FILES['image']['name']) && !empty($_POST['productName']) && !empty($_POST['price'] && !empty($_POST['category_id'])) && !empty($_POST['tax']) && !empty($_POST['stock'])) {
        list($width, $height) = @getimagesize($_FILES['image']['name']);
        $target = "/admin/images/" . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
        ) {
            $_SESSION['file_validation_error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            header('location:../product/add_product.php');
        } elseif (($_FILES["image"]["size"] > 2000000)) {
            $_SESSION['file_validation_error'] = "Image size exceeds 2MB";
            header('location:../product/add_product.php');
        } elseif ($width <= "100" || $height <= "100") {
            $_SESSION['file_validation_error'] = "Image dimension should be within 100X100";
            header('location:../product/add_product.php');
        } else {
            $productName = $_POST['productName'];
            foreach ($result as $product) {
                $name[$i] = $product['name'];
                if ($productName == $name[$i]) {
                    $_SESSION['name_validation_error'] = "Already Taken this name";
                    header('location:../product/add_product.php');
                    die();
                }
                $i++;
            }
            $price = $_POST['price'];
            $category = $_POST['category_id'];
            $tax = $_POST['tax'];
            $stock = $_POST['stock'];
            $name = $_FILES['image']['name'];
            $destination_path = "../images/";
            $target_path = $destination_path . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
            $fetch = $pdo->prepare("insert into product(name,price,category_id,tax,stock,image) values(:productName,:price,:category_id,:tax,:stock,:name)");
            $fetch->bindParam(':productName', $productName);
            $fetch->bindParam(':price', $price);
            $fetch->bindParam(':category_id', $category);
            $fetch->bindParam(':tax', $tax);
            $fetch->bindParam(':stock', $stock);
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
        if (empty($_POST['productName'])) {
            $_SESSION['name_validation_error'] = "Please enter data..";
            header('location:../product/add_product.php');
        }
        if (empty($_POST['price'])) {
            $_SESSION['price_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
        if (empty($_POST['category_id'])) {
            $_SESSION['category_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
        if (empty($_POST['tax'])) {
            $_SESSION['tax_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
        if (empty($_POST['stock'])) {
            $_SESSION['stock_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
        if (empty($_FILES['image']['name'])) {
            $_SESSION['file_validation_error'] = "Please enter data";
            header('location:../product/add_product.php');
        }
    }
}
if (isset($_POST['productName'])) {
    $_SESSION['productName'] = $_POST['productName'];
}
if (isset($_POST['price'])) {
    $_SESSION['price'] = $_POST['price'];
}
if (isset($_POST['category_id'])) {
    $_SESSION['category'] = $_POST['category_id'];
}
if (isset($_POST['tax'])) {
    $_SESSION['tax'] = $_POST['tax'];
}
if (isset($_POST['stock'])) {
    $_SESSION['stock'] = $_POST['stock'];
}
?>
<?php include '../layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add new Product</h4>
                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="productName">Product Name</label>
                                <input id="productName" type=" text" class="form-control" placeholder="Product Name"
                                    name="productName" <?php if (isset($_SESSION['productName'])) {
    echo "value=\"" . $_SESSION['productName'] . "\"";
} ?> required>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['name_validation_error'])) {
                                        echo $_SESSION['name_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="productPrice">Product Price</label>
                                <input id="productPrice" type="number" class="form-control" placeholder="Product Price"
                                    name="price" <?php if (isset($_SESSION['price'])) {
                                        echo "value=\"" . $_SESSION['price'] . "\"";
                                    } ?> required>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['price_validation_error'])) {
                                        echo $_SESSION['price_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="productCategory">Select Category</label>
                                <select id="productCategory" class="form-control" name="category_id" required>
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
                                <label for="productTax">Tax of the Product</label>
                                <select id="productTax" class="form-control" name="tax" required>
                                    <option value="">--Select Tax--</option>
                                    <option value="5" <?php if (isset($_SESSION['tax'])) {
                                        if ($_SESSION['tax'] == "5") {
                                            echo 'selected="selected"';
                                        }
                                    } ?>>5%</option>
                                    <option value="10" <?php if (isset($_SESSION['tax'])) {
                                        if ($_SESSION['tax'] == "10") {
                                            echo 'selected="selected"';
                                        }
                                    } ?>>10%</option>
                                    <option value="15" <?php if (isset($_SESSION['tax'])) {
                                        if ($_SESSION['tax'] == "15") {
                                            echo 'selected="selected"';
                                        }
                                    } ?>>15%</option>
                                    <option value="20" <?php if (isset($_SESSION['tax'])) {
                                        if ($_SESSION['tax'] == "20") {
                                            echo 'selected="selected"';
                                        }
                                    } ?>>20%</option>
                                    <option value="25" <?php if (isset($_SESSION['tax'])) {
                                        if ($_SESSION['tax'] == "25") {
                                            echo 'selected="selected"';
                                        }
                                    } ?>>25%</option>
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
                                <label for="productStock">Product Stock</label>
                                <input id="productStock" type="number" class="form-control" placeholder="Product Stock"
                                    name="stock" <?php if (isset($_SESSION['stock'])) {
                                        echo "value=\"" . $_SESSION['stock'] . "\"";
                                    } ?> required>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['stock_validation_error'])) {
                                        echo $_SESSION['stock_validation_error'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="productImage">Image</label>
                                <input id="productImage" type="file" class="form-control" accept="" name="image"
                                    required>
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