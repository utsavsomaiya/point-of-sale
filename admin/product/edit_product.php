<?php
session_start();
include '../layout/header.php';
require '../layout/db_connect.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $fetch = $pdo->prepare("select * from product where id=:id");
    $fetch->bindParam(':id', $id);
    $fetch->execute();
    $result = $fetch->fetchAll();
    foreach ($result as $r) {
        $name = $r['name'];
        $price = $r['price'];
        $category = $r['category'];
        $tax = $r['tax'];
        if (empty($_FILES['image']['name'])) {
            $fname = $r['image'];
        } else {
            $fname = $_FILES['image']['name'];
        }
    }
}
if (isset($_POST['submit'])) {
    if (!empty($_POST['productName']) && !empty($_POST['price'])) {
        $productName = $_POST['productName'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $tax = $_POST['tax'];
        $fetch = $pdo->prepare("update product set name=:productName, price=:price, category_id=:category, tax=:tax,image=:fname where id=:id");
        $fetch->bindParam(':productName', $productName);
        $fetch->bindParam(':price', $price);
        $fetch->bindParam(':category', $category);
        $fetch->bindParam(':tax', $tax);
        $fetch->bindParam(':fname', $fname);
        $fetch->bindParam(':id', $id);
        $result = $fetch->execute();
        if (isset($result)) {
            $_SESSION['msg'] = "Update Successfully";
            header('location:../product/show_product.php');
        } else {
            $_SESSION['msg'] = 'No';
            header('location:../product/edit_product.php');
        }
    } else {
        if (empty($_POST['productName']))
            $alert = "Please enter the data..";
        if (empty($_POST['price']))
            $price_alert = "Please enter the data..";
    }
}
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Product</h4>
                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?= $id; ?>">
                                <label for="productName">Product Name</label>
                                <input id="productName" type="text" class="form-control" placeholder="Product Name" name="productName" value="<?= $name; ?>">
                                <label style="color:red;">
                                    <?php
                                    if (isset($alert)) {
                                        echo $alert;
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="productPrice">Product Price</label>
                                <input id="productPrice" type="number" class="form-control" placeholder="Product Price" name="price" value="<?= $price; ?>">
                                <label style="color:red;">
                                    <?php
                                    if (isset($price_alert)) {
                                        echo $price_alert;
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="productCategory">Select Category</label>
                                <select id="productCategory" class="form-control" name="category">
                                    <option value="">--Select Category--</option>
                                    <?php
                                    $fetch = $pdo->prepare("select * from category");
                                    $fetch->execute();
                                    $res = $fetch->fetchAll();
                                    foreach ($res as $cat) {
                                    ?>
                                        <option value="<?= $cat['id'] ?>" <?php
                                                                            if ($category == $cat['id']) {
                                                                                echo "selected='selected'";
                                                                            } ?>><?= $cat['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productTax">Select Product Tax</label>
                                <select id="productTax" class="form-control" name="tax">
                                    <option value="">--Select Tax--</option>
                                    <option value="5" <?php if ($tax == "5") echo 'selected="selected"'; ?>>5%</option>
                                    <option value="10" <?php if ($tax == "10") echo 'selected="selected"'; ?>>10%</option>
                                    <option value="15" <?php if ($tax == "15") echo 'selected="selected"'; ?>>15%</option>
                                    <option value="20" <?php if ($tax == "20") echo 'selected="selected"'; ?>>20%</option>
                                    <option value="25" <?php if ($tax == "25") echo 'selected="selected"'; ?>>25%</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productImage" style="margin-right: 20px;">Image</label>
                                <img id="productImage" class="img-xs rounded-circle" src="<?= '/admin/images/' . $fname ?>"><br><br>
                                <input type="file" class="form-control" accept="" name="image">
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit" onclick="toast()">Submit</button>
                            <a href="../product/show_product.php" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layout/footer.php'; ?>