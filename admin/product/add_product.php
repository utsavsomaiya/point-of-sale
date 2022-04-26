<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_POST['submit'])) {
        if (empty($_POST['product_name'])) {
            $_SESSION['name_alert'] = "Please enter product name.";
        }
        if (empty($_POST['product_price'])) {
            $_SESSION['price_alert'] = "Please enter product price.";
        }
        if (empty($_POST['product_category_id'])) {
            $_SESSION['category_alert'] = "Please select product category.";
        }
        if (empty($_POST['product_tax'])) {
            $_SESSION['tax_alert'] = "Please select product tax.";
        }
        if (empty($_POST['product_stock'])) {
            $_SESSION['stock_alert'] = "Please enter product stock.";
        }
        if (empty($_FILES['product_image']['name'])) {
            $_SESSION['file_alert'] = "Please choose the product image.";
        }
        if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['product_category_id']) || empty($_POST['product_tax']) || empty($_POST['product_stock']) || empty($_FILES['product_image']['name'])) {
            header('location:../product/add_product.php');
            exit;
        }

        $productName = $_POST['product_name'];
        $_SESSION['product_name'] = $productName;
        $fetchProduct = $pdo->prepare('SELECT `name` FROM `product` WHERE `name` = :product_name LIMIT 1');
        $fetchProduct->bindParam(':product_name', $productName);
        $fetchProduct->execute();
        $count = $fetchProduct->rowCount();
        if ($count > 0) {
            $_SESSION['name_alert'] = "Already taken this name.";
            header('location:../product/add_product.php');
            exit;
        }

        $productPrice = $_POST['product_price'];
        $_SESSION['product_price'] = $productPrice;
        $productCategory = $_POST['product_category_id'];
        $_SESSION['product_category_id'] = $productCategory;
        $productTax = $_POST['product_tax'];
        $_SESSION['product_tax'] = $productTax;
        $productStock = $_POST['product_stock'];
        $_SESSION['product_stock'] = $productStock;

        $imageExtension = pathinfo($productImage, PATHINFO_EXTENSION);
        if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
            $_SESSION['file_alert'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            header('location:../product/add_product.php');
            exit;
        }

        if (($_FILES["product_image"]["size"] > 1000000)) {
            $_SESSION['file_alert'] = "Image size exceeds 1MB.";
            header('location:../product/add_product.php');
            exit;
        }
        $imageInfo = @getimagesize($_FILES['product_image']['tmp_name']);
        if ($imageInfo[0] != 100 || $imageInfo[1] != 100) {
            $_SESSION['file_alert'] = "Image dimension should be within 100X100.";
            header('location:../product/add_product.php');
            exit;
        }
        $destination_path = "../images/";
        $target_path = $destination_path . basename($_FILES["product_image"]["name"]);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path);
        
        $productImage = $_FILES['product_image']['name'];

        $insertProduct = $pdo->prepare("INSERT INTO `product`(`name`, `price`, `category_id`, `tax`, `stock`, `image`) VALUES(:product_name,:product_price,:product_category_id,:product_tax,:product_stock,:product_image_name)");
        $insertProduct->bindParam(':product_name', $productName);
        $insertProduct->bindParam(':product_price', $productPrice);
        $insertProduct->bindParam(':product_category_id', $productCategory);
        $insertProduct->bindParam(':product_tax', $productTax);
        $insertProduct->bindParam(':product_stock', $productStock);
        $insertProduct->bindParam(':product_image_name', $productImage);
        $isExecuted = $insertProduct->execute();
        if ($isExecuted) {
            $_SESSION['message'] = "Product added successfully.";
            header('location:../product/show_product.php');
            exit;
        }
        $_SESSION['message'] = "Something went wrong.";
        header('location:../product/add_product.php');
        exit;
    }
    $fetchCategory = $pdo->prepare("SELECT * FROM `category`");
    $fetchCategory->execute();
    $categories = $fetchCategory->fetchAll();
?>
<?php include '../layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            Add new Product
                        </h4>
                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="product-name">Product Name</label>
                                <input id="product-name" type=" text" class="form-control" placeholder="Product Name"
                                    name="product_name" required
                                    <?php
                                        if (isset($_SESSION['product_name'])) {
                                            echo "value=\"" . $_SESSION['product_name'] . "\"";
                                            unset($_SESSION['product_name']);
                                        }
                                    ?>
                                >
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['name_alert'])) {
                                            echo $_SESSION['name_alert'];
                                            unset($_SESSION['name_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="product-price">Product Price</label>
                                <input id="product-price" type="number" class="form-control" placeholder="Product Price"
                                    name="product_price" required
                                    <?php
                                        if (isset($_SESSION['product_price'])) {
                                            echo "value=\"" . $_SESSION['product_price'] . "\"";
                                            unset($_SESSION['product_price']);
                                        }
                                    ?>
                                >
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['price_alert'])) {
                                            echo $_SESSION['price_alert'];
                                            unset($_SESSION['price_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="product-category">Select Category</label>
                                <select id="product-category" class="form-control" name="product_category_id" >
                                    <option value="">--Select Category--</option>
                                    <?php foreach ($categories as $category) { ?>
                                        <option value="<?= $category['id'] ?>"
                                        <?php
                                            if (isset($_SESSION['product_category_id'])) {
                                                echo "selected='selected'";
                                                unset($_SESSION['product_category_id']);
                                            }
                                        ?>
                                        >
                                            <?= $category['name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['category_alert'])) {
                                            echo $_SESSION['category_alert'];
                                            unset($_SESSION['category_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="product-tax">Tax of the Product</label>
                                <select id="product-tax" class="form-control" name="product_tax" required>
                                    <option value="">--Select Tax--</option>
                                    <option value="5"
                                    <?php
                                        if (isset($_SESSION['product_tax'])) {
                                            if ($_SESSION['product_tax'] == "5") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['product_tax']);
                                            }
                                        }
                                    ?>>5%</option>
                                    <option value="10"
                                    <?php
                                        if (isset($_SESSION['product_tax'])) {
                                            if ($_SESSION['product_tax'] == "10") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['product_tax']);
                                            }
                                        }
                                    ?>>10%</option>
                                    <option value="15"
                                    <?php
                                        if (isset($_SESSION['product_tax'])) {
                                            if ($_SESSION['product_tax'] == "15") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['product_tax']);
                                            }
                                        }
                                    ?>>15%</option>
                                    <option value="20"
                                    <?php
                                        if (isset($_SESSION['product_tax'])) {
                                            if ($_SESSION['product_tax'] == "20") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['product_tax']);
                                            }
                                        }
                                    ?>>20%</option>
                                    <option value="25"
                                    <?php
                                        if (isset($_SESSION['product_tax'])) {
                                            if ($_SESSION['product_tax'] == "25") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['product_tax']);
                                            }
                                        }
                                    ?>>25%</option>
                                </select>
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['tax_alert'])) {
                                            echo $_SESSION['tax_alert'];
                                            unset($_SESSION['tax_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="product-stock">Product Stock</label>
                                <input id="product-stock" type="number" class="form-control" placeholder="Product Stock"
                                    name="product_stock" required
                                    <?php
                                        if (isset($_SESSION['product_stock'])) {
                                            echo "value=\"" . $_SESSION['product_stock'] . "\"";
                                            unset($_SESSION['product_stock']);
                                        }
                                    ?>>
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['stock_alert'])) {
                                            echo $_SESSION['stock_alert'];
                                            unset($_SESSION['stock_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="product-image">Product Image</label>
                                <input id="product-image" type="file" class="form-control" accept="image/png, image/gif, image/jpeg, image/jpg" name="product_image" required>
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['file_alert'])) {
                                            echo $_SESSION['file_alert'];
                                            unset($_SESSION['file_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit">
                                Submit
                            </button>
                            <a href="../product/show_product.php" class="btn btn-light">
                                Cancel
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layout/footer.php'; ?>