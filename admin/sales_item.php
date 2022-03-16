<?php
    session_start();
    if (isset($_GET['id'])) {
        $salesId = $_GET['id'];
        require 'layout/db_connect.php';
        $fetchSalesItem = $pdo->prepare('SELECT sales_item.product_id, product.name, product.image, sales_item.product_quantity, sales_item.product_price, sales_item.product_total_price,discount_tier.discount_digit,discount.type, sales_item.product_discount,sales_item.product_taxable_price,sales_item.product_tax_percentage,sales_item.product_tax_amount,sales.subtotal,sales.total_tax,sales.discount,sales.total FROM sales_item JOIN product ON sales_item.product_id = product.id JOIN sales ON sales_item.sales_id = sales.id AND sales_item.sales_id = :sales_id JOIN discount_tier ON discount_tier.tier_id = sales_item.product_discount_tier_id JOIN discount ON discount.id = sales_item.product_discount_id');
        $fetchSalesItem->bindParam(':sales_id', $salesId);
        $fetchSalesItem->execute();
        $salesItems = $fetchSalesItem->fetchAll();
    } else {
        header('location:/admin/sales.php');
        exit;
    }
?>
<?php include 'layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="card-body" style="Overflow-x:scroll;">
                <h4 class="card-title">
                    <span>Products</span>
                </h4>
                <?php if (sizeof($salesItems) > 0) { ?>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total price</th>
                                <th>Discount Rate</th>
                                <th>Discount</th>
                                <th>Taxable price</th>
                                <th>Tax percentage</th>
                                <th>Total Tax</th>
                            </tr>
                            <?php foreach ($salesItems as $salesItem) { ?>
                                <tr>
                                    <td><?= $salesItem['product_id'] ?></td>
                                    <td><?= $salesItem['name'] ?></td>
                                    <td><img src="<?= '/admin/images/' . $salesItem['image'] ?>"></td>
                                    <td><?= "$".$salesItem['product_price'] ?></td>
                                    <td><?= $salesItem['product_quantity'] ?></td>
                                    <td><?= "$".$salesItem['product_total_price'] ?></td>
                                    <td>
                                        <?php
                                            if ($salesItem['type'] == "1") {
                                                echo $salesItem['discount_digit']."%";
                                            } else {
                                                echo "$".$salesItem['discount_digit'];
                                            }
                                        ?>
                                    </td>
                                    <td><?= "$".$salesItem['product_discount'] ?></td>
                                    <td><?= "$".$salesItem['product_taxable_price'] ?></td>
                                    <td><?= $salesItem['product_tax_percentage']."%" ?></td>
                                    <td><?= "$".$salesItem['product_tax_amount'] ?></td>
                                </tr>
                            <?php }  ?>
                        </tbody>
                    </table>
                    </div>
                    <div>
                        <span style="padding-left: 50px;padding-top: 100px;padding-bottom: 100px;padding-right: 50px">
                            Thank You!
                        </span>
                        <div style="padding-left: 750px;padding-top: 20px;">
                            <b>
                                <span style="padding-right: 30px;">SUBTOTAL = </span>
                                <span><?= "$".$salesItems[0]['subtotal']; ?></span>
                                <br>
                                <span style="padding-right: 40px;">Discount = </span>
                                <span><?= "-"."$".$salesItems[0]['discount']; ?></span>
                                <br>
                                <span style="padding-right: 80px;">TAX = </span>
                                <span><?= "+"."$".$salesItems[0]['total_tax']; ?></span>
                                <br>
                                <span>=================</span>
                                <br>
                                <span style="padding-right: 10px;">GRAND TOTAL = </span>
                                <span><?= "$".$salesItems[0]['total']; ?></span>
                                <br>
                            </b>
                        </div>
                    </div>
                <?php
                    } else {
                        echo "No Record Found..";
                    }
                ?>
        </div>
    </div>
</div>
<?php include 'layout/footer.php';?>
