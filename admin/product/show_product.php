<?php
    session_start();
    require '../layout/db_connect.php';
    $fetchProduct = $pdo->prepare('SELECT p.id,p.name,p.price,c.name as category_name,p.tax,p.stock,p.image FROM product p JOIN category c ON c.id = p.category_id ORDER BY p.id DESC');
    $fetchProduct->execute();
    $products = $fetchProduct->fetchAll();
?>
<?php include '../layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card-body">
                    <h4 class="card-title">
                        <span style="margin-right:80px;">Products</span>
                        <a href="add_product.php">Add New Product</a>
                    </h4>
                    <?php if (sizeof($products) > 0) { ?>
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
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= $product['name'] ?></td>
                                <td><?= "$" . $product['price'] ?></td>
                                <td><?= $product['category_name'] ?></td>
                                <td><?= $product['tax'] . "%" ?></td>
                                <td><?= $product['stock'] ?></td>
                                <td><img src="<?= '/admin/images/' . $product['image'] ?>"></td>
                                <td>
                                    <a href="../product/edit_product.php?id=<?= $product['id'] ?>">
                                    <img src="/admin/image/edit-icon.png" />
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:deleteProduct(<?= $product['id'] ?>)">
                                    <i class="fa fa-trash-o" style="font-size:24px"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
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
<?php include '../layout/footer.php'; ?>