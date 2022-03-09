<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $productId = $_GET['id'];
        $fetchProduct = $pdo->prepare("SELECT * FROM `product` WHERE `id` = :id");
        $fetchProduct->bindParam(':id', $productId);
        $fetchProduct->execute();
        $products = $fetchProduct->fetchAll();
        foreach ($products as $product) {
            $productName = $product['name'];
            $productPrice = $product['price'];
            $categoryId = $product['category_id'];
            $productTax = $product['tax'];
            $productStock = $product['stock'];
            $productImage = $product['image'];
        }
    }
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
        if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['product_category_id']) || empty($_POST['product_tax']) || empty($_POST['product_stock'])) {
            header("location:../product/edit_product.php?id=$productId");
            exit;
        }

        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $productCategory = $_POST['product_category_id'];
        $productTax = $_POST['product_tax'];
        $productStock = $_POST['product_stock'];

        if (!empty($_FILES['product_image']['name'])) {
            $productImage = $_FILES['product_image']['name'];
            $imageExtension = pathinfo($productImage, PATHINFO_EXTENSION);
            if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
                $_SESSION['file_alert'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                header("location:../product/edit_product.php?id=$productId");
                exit;
            }
            if (($_FILES["product_image"]["size"] > 1000000)) {
                $_SESSION['file_alert'] = "Image size exceeds 1MB";
                header("location:../product/edit_product.php?id=$productId");
                exit;
            }
            $imageInfo = @getimagesize($_FILES['product_image']['tmp_name']);
            if ($imageInfo[0] != 100 || $imageInfo[1] != 100) {
                $_SESSION['file_alert'] = "Image dimension should be within 100X100.";
                header("location:../product/edit_product.php?id=$productId");
                exit;
            }
            $destination_path = "../images/";
            $target_path = $destination_path . basename($_FILES["product_image"]["name"]);
            move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path);
        }

        $updateProduct = $pdo->prepare("UPDATE `product` SET `name` = :product_name, `price`= :product_price, `category_id` = :product_category, `tax` = :product_tax, `stock` = :product_stock, `image` = :product_image WHERE `id` = :product_id");
        $updateProduct->bindParam(':product_name', $productName);
        $updateProduct->bindParam(':product_price', $productPrice);
        $updateProduct->bindParam(':product_category', $productCategory);
        $updateProduct->bindParam(':product_tax', $productTax);
        $updateProduct->bindParam(':product_stock', $productStock);
        $updateProduct->bindParam(':product_image', $productImage);
        $updateProduct->bindParam(':product_id', $productId);
        $isExecuted = $updateProduct->execute();
        if ($isExecuted) {
            $_SESSION['message'] = "Product updated successfully.";
            header('location:../product/show_product.php');
            exit;
        }
        $_SESSION['message'] = 'Something went wrong.';
        header("location:../product/edit_product.php?id=$productId");
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
                            Edit Product
                        </h4>
                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="product-name">Product Name</label>
                                <input id="product-name" type="text" class="form-control" placeholder="Product Name"
                                    name="product_name" required
                                    <?php
                                        if (isset($productName)) {
                                            echo "value=\"" . $productName . "\"";
                                        }
                                    ?>>
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
                                        if (isset($productPrice)) {
                                            echo "value=\"" . $productPrice . "\"";
                                        }
                                    ?>>
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
                                <select id="product-category" class="form-control" name="product_category_id" required>
                                    <option value="">--Select Category--</option>
                                    <?php foreach ($categories as $category) { ?>
                                        <option value="<?= $category['id'] ?>"
                                        <?php
                                            if ($category['id'] == $categoryId) {
                                                echo "selected='selected'";
                                            }
                                        ?>>
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
                                <label for="product-tax">Select Product Tax</label>
                                <select id="product-tax" class="form-control" name="product_tax" required>
                                    <option value="">--Select Tax--</option>
                                    <option value="5"
                                    <?php
                                        if ($productTax == "5") {
                                            echo 'selected="selected"';
                                        }
                                    ?>>5%</option>
                                    <option value="10"
                                    <?php
                                        if ($productTax == "10") {
                                            echo 'selected="selected"';
                                        }
                                    ?>>10%</option>
                                    <option value="15"
                                    <?php
                                        if ($productTax == "15") {
                                            echo 'selected="selected"';
                                        }
                                    ?>>15%</option>
                                    <option value="20"
                                    <?php
                                        if ($productTax == "20") {
                                            echo 'selected="selected"';
                                        }
                                    ?>>20%</option>
                                    <option value="25"
                                    <?php
                                        if ($productTax == "25") {
                                            echo 'selected="selected"';
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
                                        if (isset($productStock)) {
                                            echo "value=\"" . $productStock . "\"";
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
                                <label for="product-image" style="margin-right: 20px;">
                                    Product Image
                                </label>
                                <?php if (isset($productImage)) {?>
                                    <img  class="img-xs rounded-circle" src="<?= '/admin/images/' . $productImage ?>">
                                    <br>
                                    <br>
                                <?php } ?>
                                <input id="product-image" type="file" class="form-control" accept="image/png, image/gif, image/jpeg, image/jpg" name="product_image">
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['file_alert'])) {
                                            echo $_SESSION['file_alert'];
                                            unset($_SESSION['file_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit" onclick="toast()">
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