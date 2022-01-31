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
                                    <th>Id</th>
                                    <th>Sales_id</th>
                                    <th>Product_id</th>
                                    <th>Product_Name</th>
                                    <th>Product_Image</th>
                                    <th>Product_Quantity</th>
                                    <th>Product_tax_percentage</th>
                                </tr>
                            <?php
                                require 'layout/db_connect.php';
                                $fetch = $pdo->prepare('select * from sales_item where sales_id =:sales_id');
                                $fetch->bindParam(':sales_id', $salesId);
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
                                        $fetch = $pdo->prepare('select name,image from product where id=:id');
                                        $fetch->bindParam(':id', $salesItem['product_id']);
                                        $fetch->execute();
                                        $result = $fetch->fetchAll();
                                        foreach ($result as $productItem) {
                                            if (!empty($productItem)) {
                                                ?>
                                    <td><?= $productItem['name'] ?></td>
                                    <td><img src="<?= '/admin/images/' . $productItem['image'] ?>"></td>
                                    <?php
                                            } else {
                                                echo "No Record Found..";
                                            }
                                        } ?>
                                    <td><?= $salesItem['product_price'] ?></td>
                                    <td><?= $salesItem['product_tax_percentage'] ?></td>
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
