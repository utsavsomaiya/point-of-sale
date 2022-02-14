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
                        <span style="margin-right:80px;">Invoice</span>
                    </h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Product id</th>
                                <th>Product Name</th>
                                <th>Product Image</th>
                                <th>Product Quantity</th>
                                <th>Product tax percentage</th>
                                <th>Product Price</th>
                                <th>Product total price</th>
                            </tr>
                            <?php
                                require 'layout/db_connect.php';
                                $fetch = $pdo->prepare('SELECT sales_item.product_id, product.name, product.image, sales_item.product_quantity, sales_item.product_price, sales_item.product_total_price, sales_item.product_tax_percentage FROM sales_item JOIN product ON sales_item.product_id = product.id AND sales_item.sales_id = :sales_id');
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
                                <td><?= $salesDetails['product_tax_percentage'] ?></td>
                                <td><?= $salesDetails['product_price'] ?></td>
                                <td><?=   $salesDetails['product_total_price'] ?></td>
                            </tr>
                            <?php
                                    } else {
                                        echo "No Record Found..";
                                    }
                                } ?>
                            </td>
                            </tr>
                        </tbody>
                        <?php
                            $fetch = $pdo->prepare('SELECT `subtotal`,`discount`,`total_tax`,`total` from `sales` WHERE `id` = :id');
                            $fetch->bindParam(':id', $salesId);
                            $fetch->execute();
                            $result = $fetch->fetchAll();
                            foreach ($result as $sales) {
                                if (!empty($sales)) {
                                    ?>
                        <tfoot>
                            <tr>
                                <th colspan="4"></th>
                                <th>=================</th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th>SUBTOTAL</th>
                                <th><?= $sales['subtotal']; ?></th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th>TAX</th>
                                <th><?= $sales['total_tax']; ?></th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th>Discount</th>
                                <th><?= $sales['discount']; ?></th>
                            </tr>
                            <tr>
                                <th>Thank you!</th>
                                <th colspan="3"></th>
                                <th>GRAND TOTAL</th>
                                <th><?= $sales['total']; ?></th>
                            </tr>
                        </tfoot>
                        <?php
                                } else {
                                    echo "No Record Found..";
                                }
                            }
                        ?>
                    </table>

                </div>
            </div>
        </div>
        <table>
    </div>
</div>
<?php include 'layout/footer.php';?>
