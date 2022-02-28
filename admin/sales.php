<?php
session_start();
include 'layout/header.php';
?>
<div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-13 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Sales Details</h4>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Subtotal</th>
                          <th>Total tax</th>
                          <th>Discount</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            require 'layout/db_connect.php';
                            $fetch = $pdo->prepare('select * from sales');
                            $fetch->execute();
                            $result = $fetch->fetchAll();
                            foreach ($result as $salesRecord) {
                                if (!empty($salesRecord)) {
                                    ?>
                        <tr onclick="window.location = '/admin/sales_item.php?id=<?= $salesRecord['id'] ?>'" class="thumbnail">
                                    <td><?= $salesRecord['id'] ?></td>
                                    <td><?= "$".$salesRecord['subtotal'] ?></td>
                                    <td><?= "$".$salesRecord['total_tax'] ?></td>
                                    <td><?= "$".$salesRecord['discount'] ?></td>
                                    <td><?= "$".$salesRecord['total']?></td>
                        </tr>
                        <?php
                                } else {
                                    echo "No Record Found..";
                                }
                            }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<?php include 'layout/footer.php';?>
