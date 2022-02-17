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
                        <span>Sales Bill</span>
                    </h4>
                    <table class="table">
                            <thead>
                                <tr class="hvr-underline-from-center">
                                    <th>Id</th>
                                    <th>Subtotal</th>
                                    <th>Total tax</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>


                                <?php
                                require 'layout/db_connect.php';
                                $fetch = $pdo->prepare('select * from sales');
                                $fetch->execute();
                                $result = $fetch->fetchAll();
                                foreach ($result as $salesRecord) {
                                    if (!empty($salesRecord)) {
                                        ?>
                                <tr onclick="window.location = '/admin/sales_item.php?id=<?= $salesRecord['id'] ?>'"    class="hvr-underline-from-center">
                                    <td><?= $salesRecord['id'] ?></td>
                                    <td><?= $salesRecord['subtotal'] ?></td>
                                    <td><?= $salesRecord['total_tax'] ?></td>
                                    <td><?= $salesRecord['discount'] ?></td>
                                    <td><?= $salesRecord['total']?></td>
                                </tr>
                                <?php
                                    } else {
                                        echo "No Record Found..";
                                    }
                                }
                                ?>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/footer.php';?>
