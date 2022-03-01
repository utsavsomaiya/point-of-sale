<?php
session_start();
include '../layout/header.php';
require '../layout/db_connect.php';
$fetch = $pdo->prepare('SELECT * FROM `product` ORDER BY `id` DESC');
$fetch->execute();
$result = $fetch->fetchAll();
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
                        if (sizeof($result) > 0) {
                            ?>
                    <table class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Tax</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th colspan='2'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $product) {
                                    ?>
                                <tr>
                                    <td><?= $product['id'] ?></td>
                                    <td><?= $product['name'] ?></td>
                                    <td><?= "$" . $product['price'] ?></td>
                                    <td>
                                    <?php
                                    $fetch = $pdo->prepare("SELECT `name` FROM `category` WHERE `id` = :id ");
                                    $fetch->bindParam(':id', $product['category_id']);
                                    $fetch->execute();
                                    $result = $fetch->fetchAll();
                                    foreach ($result as $category) {
                                        if (sizeof($category) > 0) {
                                            echo $category['name'];
                                        }
                                    } ?>
                                    </td>
                                    <td><?= $product['tax'] . "%" ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td><img src="<?= '/admin/images/' . $product['image'] ?>"></td>
                                    <td><a href="../product/edit_product.php?id=<?= $product['id'] ?>"><img
                                                src="/admin/image/edit-icon.png" /></a></td>
                                    <td><a href="javascript:deleteProduct(<?= $product['id'] ?>)"><i class="fa fa-trash-o" style="font-size:24px"></i></a>
                                    </td>
                                </tr>
                                <?php
                                } ?>
                            </tbody>
                        </form>
                    </table>
                    <?php
                        } else {
                            echo "No Record Found..";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layout/footer.php';
unset($_SESSION['msg']);
?>