<?php
    session_start();
    if (isset($_GET['id'])) {
        $salesId = $_GET['id'];
        require 'layout/db_connect.php';
        $fetchSalesItem = $pdo->prepare('SELECT * FROM `sales_item` WHERE `sales_id` = :sales_id');
        $fetchSalesItem->bindParam(':sales_id', $salesId);
        $fetchSalesItem->execute();
        $salesItems = $fetchSalesItem->fetchAll();

        $fetchSales = $pdo->prepare('SELECT * FROM `sales` WHERE `id` = :sales_id');
        $fetchSales->bindParam(':sales_id', $salesId);
        $fetchSales->execute();
        $sales = $fetchSales->fetchAll();

        $fetchDiscount = $pdo->prepare('SELECT discount.type,discount_tier.discount_digit FROM discount,discount_tier WHERE discount.id = :discount_id AND discount_tier.tier_id = :discount_tier_id');

        $fetchProduct = $pdo->prepare('SELECT `name`,`image` FROM `product` WHERE id = :id');

        $fetchDiscountProduct = $pdo->prepare('SELECT `image` FROM `product` WHERE `name` = :name');
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
                                    <?php
                                        $fetchProduct->bindParam(':id', $salesItem['product_id']);
                                        $fetchProduct->execute();
                                        $product = $fetchProduct->fetchAll();
                                    ?>
                                    <td><?= $product[0]['name'] ?></td>
                                    <td><img src="<?= '/admin/images/' . $product[0]['image'] ?>"></td>
                                    <td><?= "$".$salesItem['product_price'] ?></td>
                                    <td><?= $salesItem['product_quantity'] ?></td>
                                    <td><?= "$".$salesItem['product_total_price'] ?></td>
                                    <?php if ($salesItem['product_discount'] != 0) {?>
                                        <?php
                                            $fetchDiscount->bindParam(':discount_id', $salesItem['product_discount_id']);
                                            $fetchDiscount->bindParam(':discount_tier_id', $salesItem['product_discount_tier_id']);
                                            $fetchDiscount->execute();
                                            $discount = $fetchDiscount->fetchAll();
                                        ?>
                                        <td>
                                            <?php
                                                if ($discount[0]['type'] == "1") {
                                                    echo $discount[0]['discount_digit']."%";
                                                } else {
                                                    echo "$".$discount[0]['discount_digit'];
                                                }
                                            ?>
                                        </td>
                                        <td><?= "$".$salesItem['product_discount'] ?></td>
                                    <?php } elseif ($salesItem['product_discount'] == 0 && $salesItem['product_discount_tier_id'] != null) { ?>
                                        <td>NULL</td>
                                        <?php
                                            $fetchDiscountProduct->bindParam(':name', $sales[0]['gift_discount']);
                                            $fetchDiscountProduct->execute();
                                            $discountProduct = $fetchDiscountProduct->fetchAll();
                                        ?>
                                        <td>
                                            <img src="/admin/images/<?= $discountProduct[0]['image'] ?>">
                                        </td>
                                    <?php } else { ?>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                    <?php } ?>
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
                                <span><?= "$".$sales[0]['subtotal']; ?></span>
                                <br>
                                <?php if ($sales[0]['price_discount'] != 0) {?>
                                    <span style="padding-right: 40px;">Discount = </span>
                                    <span><?= "-"."$".$sales[0]['price_discount']; ?></span>
                                    <br>
                                <?php } ?>
                                <span style="padding-right: 80px;">TAX = </span>
                                <span><?= "+"."$".$sales[0]['total_tax']; ?></span>
                                <br>
                                <span>=================</span>
                                <br>
                                <span style="padding-right: 10px;">GRAND TOTAL = </span>
                                <span><?= "$".$sales[0]['total']; ?></span>
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
