<?php
    session_start();
    if (isset($_GET['id'])) {
        $salesId = $_GET['id'];
        require 'layout/db_connect.php';
        $fetchSalesItem = $pdo->prepare('SELECT * FROM sales_item,sales WHERE sales_item.sales_id = :sales_id AND sales.id = :sales');
        $fetchSalesItem->bindParam(':sales_id', $salesId);
        $fetchSalesItem->bindParam(':sales', $salesId);
        $fetchSalesItem->execute();
        $salesItems = $fetchSalesItem->fetchAll();
        foreach ($salesItems as $keys=>$salesItem) {
            $productId[$keys] = $salesItem['product_id'];
            $discountId = $salesItem['discount_id'];
            $discountTierId = $salesItem['discount_tier_id'];
        }
        for ($i=0;$i<sizeof($productId);$i++) {
            $fetchProducts = $pdo->prepare("SELECT * FROM product WHERE `id` = $productId[$i]");
            $fetchProducts->execute();
            $products[$i] = $fetchProducts->fetchAll();
        }
        if (is_null($discountId)) {
            $discountId = "NULL";
        }
        if (is_null($discountTierId)) {
            $discountTierId = "NULL";
        }
        $fetchDiscounts = $pdo->prepare("SELECT * FROM discount,discount_tier WHERE discount.id = $discountId AND discount_tier.tier_id = $discountTierId");
        $fetchDiscounts->execute();
        $discount = $fetchDiscounts->fetchAll();
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
                            <?php foreach ($salesItems as $key=>$salesItem) { ?>
                                <tr>
                                    <td><?= $salesItem['product_id'] ?></td>
                                    <td>
                                        <?= $products[$key][0]['name'] ?>
                                        <?php if ($key == sizeof($salesItems) - 1 && $salesItem['discount_category'] == "gift") { ?>
                                            <span class="bg-warning text-white">Free</span>
                                        <?php } ?>
                                    </td>
                                    <td><img src="<?= '/admin/images/' . $products[$key][0]['image'] ?>"></td>
                                    <td><?= "$".$salesItem['product_price'] ?></td>
                                    <td><?= $salesItem['product_quantity'] ?></td>
                                    <td><?= "$".$salesItem['product_total_price'] ?></td>
                                    <td>
                                        <?php
                                        if ($discountId == "NULL") {
                                            echo "$0";
                                        } else {
                                            if ($discount[0]['type'] == "1") {
                                                echo $discount[0]['discount_digit']."%";
                                            } elseif (($discount[0]['type'] == "2")) {
                                                echo "$".$discount[0]['discount_digit'];
                                            } else {
                                                echo "$".$salesItem['product_discount'];
                                            }
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
                                <span><?= "-"."$".$salesItems[0]['total_discount']; ?></span>
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
