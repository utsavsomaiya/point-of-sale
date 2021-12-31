<?php
    session_start();
    include '../layout/header.php';
    try {
        $pdo =  new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $fetch = $pdo->prepare("select * from product where id='$id'");
        $fetch->execute();
        $result = $fetch->fetchAll();
        foreach ($result as $r) {
            $name = $r['name'];
            $price = $r['price'];
            if (empty($_FILES['image']['name'])) {
                $fname = $r['image'];
            } else {
                $fname=$_FILES['image']['name'];
            }
        }
    }
    if (isset($_POST['submit'])) {
        if (!empty($_POST['pname']) && !empty($_POST['price'])) {
            if (is_numeric($_POST['price'])) {
                $pname = $_POST['pname'];
                $price = $_POST['price'];
                $fetch = $pdo->prepare("update product set name='$pname', price='$price', image='$fname' where id='$id'");
                $result = $fetch->execute();
                if (isset($result)) {
                    $_SESSION['msg'] = "Update Successfully";
                    header('location:../product/show_product.php');
                } else {
                    echo 'No';
                }
            } else {
                $price_alert = "Enter Only Numeric Value";
            }
        } else {
            $alert = "Please enter the data..";
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
                                <label for="exampleInputUsername1">Product Name</label>
                                <input type="text" class="form-control" id="exampleInputUsername1"
                                    placeholder="Product Name" name="pname" value="<?= $name; ?>">
                                    <label style="color:red;">
                                    <?php
                                    if (isset($alert)) {
                                        echo $alert;
                                    }
                                    ?>
                                    </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product Price</label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                    placeholder="Product Price" name="price" value="<?= $price; ?>">
                                    <label style="color:red;">
                                    <?php
                                    if (isset($price_alert)) {
                                        echo $price_alert;
                                    }
                                    ?>
                                    </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1" style="margin-right: 20px;">Image</label>
                                <img class="img-xs rounded-circle" src="<?= '/images/'.$fname ?>"><br><br>
                                <input type="file" class="form-control" accept="" name="image">
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit" onclick="myFunction()">Submit</button>
                            <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layout/footer.php'; ?>