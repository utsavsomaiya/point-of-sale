<?php
session_start();
include 'layout/header.php';
$salesId = $_GET['id'];
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
                <div class="card-body" style="Overflow-x:scroll;">
                    <h4 class="card-title">
                        <span>Invoice</span>
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
                        </table>
                </div>
                <div>
                    <span style="
    padding-left: 50px;
    padding-top: 100px;
    padding-bottom: 100px;
    padding-right: 50px;">Thank You!</spn>
                        <?php
                            $fetch = $pdo->prepare('SELECT `subtotal`,`discount`,`total_tax`,`total` from `sales` WHERE `id` = :id');
                            $fetch->bindParam(':id', $salesId);
                            $fetch->execute();
                            $result = $fetch->fetchAll();
                            foreach ($result as $sales) {
                                if (!empty($sales)) {
                                    ?>
                               <div style="padding-left: 750px;padding-top: 20px;">
                                   <b>
                                       <span style="padding-right: 30px;">SUBTOTAL = </span>
                                       <?= $sales['subtotal']; ?><br>
                                       <span style="padding-right: 80px;">TAX = </span>
                                       <?= "+".$sales['total_tax']; ?><br>
                                       <span style="padding-right: 40px;">Discount = </span>
                                       <?= "-".$sales['discount']; ?><br><br>
                                       <span>===============</span><br>
                                       <span style="padding-right: 10px;">GRAND TOTAL = </span>
                                       <?= $sales['total']; ?><br>
                                   </b>
                               </div>
                        <?php
                                } else {
                                    echo "No Record Found..";
                                }
                            }
                        ?>

            </div>
        </div>
    </div>
</div>
<?php include 'layout/footer.php';?>
