<?php
    session_start();
    require 'layout/db_connect.php';
    $fetchSales = $pdo->prepare('SELECT sales.*,discount.type,discount_tier.discount_digit FROM `sales`,`discount`,`discount_tier` WHERE discount.id = sales.discount_id AND discount_tier.tier_id = sales.discount_tier_id ORDER BY `id` DESC');
    $fetchSales->execute();
    $sales = $fetchSales->fetchAll();
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
                                            <td>
                                                <?php
                                                    if ($sale['type'] == "1") {
                                                        echo $sale['discount_digit']."%";
                                                    } else {
                                                        echo "$".$sale['discount_digit'];
                                                    }
                                                    ?>
                                            </td>
                                            <td><?= "$".$sale['discount'] ?></td>
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
