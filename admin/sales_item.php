<?php
session_start();
include 'layout/header.php';
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card-body">
                    <h4 class="card-title">
                        <span style="margin-right:80px;">Sales Items</span>
                    </h4>
                    <table class="table">
                        <form method="post">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Sales_id</th>
                                    <th>Product_id</th>
                                    <th>Product_Name</th>
                                    <th>Product_Image</th>
                                    <th>Product_Quantity</th>
                                    <th>Product_tax_percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require 'layout/db_connect.php';
                                $fetch = $pdo->prepare('select * from sales_item');
                                $fetch->execute();
                                $result = $fetch->fetchAll();
                                foreach ($result as $salesItem) {
                                    if (!empty($salesItem)) {
                                        ?>
                                <tr>
                                    <td><?= $salesItem['id'] ?></td>
                                    <td><?= $salesItem['sales_id'] ?></td>
                                    <td><?= $salesItem['product_id'] ?></td>
                                    <?php
                                        $productId = $salesItem['product_id'];
                                        $fetch = $pdo->prepare("select name,image from product where id =:id");
                                        $fetch->bindParam(':id', $productId);
                                        $fetch->execute();
                                        $result = $fetch->fetchAll();
                                        foreach ($result as $product) {
                                            if (!empty($salesItem)) {
                                                ?>
                                    <td><?= $product['name'] ?></td>
                                    <td><img src="<?= '/admin/images/' . $product['image'] ?>"></td>
                                    <?php
                                            } else {
                                                echo "No Record Found..";
                                            }
                                        } ?>
                                    <td><?= $salesItem['product_quantity'] ?></td>
                                    <td><?= $salesItem['product_tax_percentage'] ?></td>
                                </tr>
                                <?php
                                    } else {
                                        echo "No Record Found..";
                                    }
                                }
                                ?>
                            </tbody>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/footer.php';?>
