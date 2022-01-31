<?php
session_start();
include 'layout/header.php';
$salesId = $_GET['id'];
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card-body">
                    <h4 class="card-title">
                        <span style="margin-right:80px;">Sales Details</span>
                    </h4>
                    <table class="table">
                                <tr>
                                    <th>Product_id</th>
                                    <th>Product_Name</th>
                                    <th>Product_Image</th>
                                    <th>Product_Quantity</th>
                                    <th>Product_Price</th>
                                    <th>Product_tax_percentage</th>
                                </tr>
                            <?php
                                require 'layout/db_connect.php';
                                $fetch = $pdo->prepare('SELECT sales_item.product_id, product.name, product.image, sales_item.product_quantity, sales_item.product_price, sales_item.product_tax_percentage FROM sales_item JOIN product ON sales_item.product_id = product.id AND sales_item.sales_id = :sales_id');
                                $fetch->bindParam(':sales_id', $salesId);
                                $fetch->execute();
                                $result = $fetch->fetchAll();
                                foreach ($result as $salesDetails) {
                                    if (!empty($salesDetails)) {
                                        ?>
                                <tr>
                                    <td><?= $salesDetails['product_id'] ?></td>
                                    <td><?= $salesDetails['name'] ?></td>
                                    <td><img src="<?= '/admin/images/' . $salesDetails['image'] ?>"></td>
                                    <td><?= $salesDetails['product_quantity'] ?></td>
                                    <td><?= $salesDetails['product_price'] ?></td>
                                    <td><?= $salesDetails['product_tax_percentage'] ?></td>
                                </tr>
                                <?php
                                    } else {
                                        echo "No Record Found..";
                                    }
                                } ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/footer.php';?>
