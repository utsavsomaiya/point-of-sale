<?php
    session_start();
    include 'layout/header.php';
    require 'layout/db_connect.php';
?>
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
								<?php
                                        $fetch = $pdo->prepare("SELECT COUNT(*) as `total_sales` FROM `sales`");
                                        $fetch->execute();
                                        $result = $fetch->fetchAll();
                                        foreach ($result as $salesCount) {
                                            echo $salesCount['total_sales'];
                                        }
                                ?>
							</h3>
                          </div>
                          <div>
                            <p class="statistics-title">Total sales amount</p>
                            <h3 class="rate-percentage">
								<?php
                                        $fetch = $pdo->prepare("SELECT SUM(`total`) as `sales_total` FROM `sales`");
                                        $fetch->execute();
                                        $result = $fetch->fetchAll();
                                        foreach ($result as $salesTotal) {
                                            echo round($salesTotal['sales_total'], 2);
                                        }
                                ?>
							</h3>
                          </div>
                          <div>
                            <p class="statistics-title">Total discount offered</p>
                            <h3 class="rate-percentage">
								<?php
                                        $fetch = $pdo->prepare("SELECT SUM(`discount`) as `total_discount` FROM `sales`");
                                        $fetch->execute();
                                        $result = $fetch->fetchAll();
                                        foreach ($result as $salesDiscount) {
                                            echo round($salesDiscount['total_discount'], 2);
                                        }
                                ?>
							</h3>
                          </div>
                          <div class="d-none d-md-block">
                            <p class="statistics-title">Total tax collected</p>
                            <h3 class="rate-percentage">
								<?php
                                        $fetch = $pdo->prepare("SELECT SUM(`total_tax`) as `total_tax` FROM `sales`");
                                        $fetch->execute();
                                        $result = $fetch->fetchAll();
                                        foreach ($result as $salesTax) {
                                            echo round($salesTax['total_tax'], 2);
                                        }
                                ?>
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
<?php
    include 'layout/footer.php';
?>
