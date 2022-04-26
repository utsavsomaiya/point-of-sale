<?php
    session_start();
    require 'layout/db_connect.php';
    $fetchSales = $pdo->prepare('SELECT * FROM `sales` ORDER BY `id` DESC');
    $fetchSales->execute();
    $sales = $fetchSales->fetchAll();

    $fetchProduct = $pdo->prepare('SELECT `image` FROM `product` WHERE `name` = :name');

    $fetchDiscount = $pdo->prepare('SELECT discount.type,discount_tier.discount_digit FROM discount,discount_tier WHERE discount.id = :discount_id AND discount_tier.tier_id = :discount_tier_id');
?>
<?php include 'layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card-body">
                    <h4 class="card-title">
                        Sales Details
                    </h4>
                    <div class="table-responsive">
                        <?php if (sizeof($sales) > 0) { ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Subtotal</th>
                                        <th>Discount Rate</th>
                                        <th>Discount</th>
                                        <th>Total tax</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sales as $sale) { ?>
                                        <tr onclick="window.location = '/admin/sales_item.php?id=<?= $sale['id'] ?>'" class="thumbnail">
                                            <td><?= $sale['id'] ?></td>
                                            <td><?= "$".$sale['subtotal'] ?></td>
                                            <?php if ($sale['price_discount'] != null) {?>
                                                <?php
                                                    $fetchDiscount->bindParam(':discount_id', $sale['discount_id']);
                                                    $fetchDiscount->bindParam(':discount_tier_id', $sale['discount_tier_id']);
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
                                                <td><?= "$".$sale['price_discount'] ?></td>
                                            <?php } elseif ($sale['gift_discount'] != null) { ?>
                                                <td>NULL</td>
                                                <?php
                                                    $fetchProduct->bindParam(':name', $sale['gift_discount']);
                                                    $fetchProduct->execute();
                                                    $product = $fetchProduct->fetchAll();
                                                ?>
                                                <td><img src="/admin/images/<?= $product[0]['image'] ?>"></td>
                                            <?php } else { ?>
                                                <td>NULL</td>
                                                <td>NULL</td>
                                            <?php } ?>
                                            <td><?= "$".$sale['total_tax'] ?></td>
                                            <td><?= "$".$sale['total']?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php
                            } else {
                                echo "No sales found.";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/footer.php';?>
