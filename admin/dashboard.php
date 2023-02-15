<?php
    session_start();
    require 'layout/db_connect.php';
    $salesDetails = $pdo->prepare("SELECT COUNT(*) as `total_sales`, SUM(`total`) as `sales_total`, SUM(`total_discount`) as `total_discount`, SUM(`total_tax`) as `total_tax`, COUNT(IF(sales.discount_category = 'gift',sales.discount_category,NULL)) as `gift_discount` FROM `sales`");
    $salesDetails->execute();
    $salesDetails = $salesDetails->fetchAll();
?>
<?php include 'layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="statistics-title">Total sales count</p>
                                            <h3 class="rate-percentage">
                                                <?= $salesDetails[0]['total_sales'] ?>
                                            </h3>
                                        </div>
                                        <div>
                                            <p class="statistics-title">Total sales amount</p>
                                            <h3 class="rate-percentage">
                                                <?= round($salesDetails[0]['sales_total'], 2)?>
                                            </h3>
                                        </div>
                                        <div>
                                            <p class="statistics-title">Total discount offered</p>
                                            <h3 class="rate-percentage">
                                                <?= round($salesDetails[0]['total_discount'], 2)?>
                                            </h3>
                                        </div>
                                        <div>
                                            <p class="statistics-title">Total Gift Discount</p>
                                            <h3 class="rate-percentage">
                                                <?= $salesDetails[0]['gift_discount'] ?>
                                            </h3>
                                        </div>
                                        <div class="d-none d-md-block">
                                            <p class="statistics-title">Total tax collected</p>
                                            <h3 class="rate-percentage">
                                                <?= round($salesDetails[0]['total_tax'], 2) ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/footer.php'; ?>