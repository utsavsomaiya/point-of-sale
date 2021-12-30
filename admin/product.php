<?php
session_start();
include 'header.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Products&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="add_product.php">Add New Product</a></h4>
                  <div class="table-responsive">
                    <table class="table">
                      <form method="post">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Name</th>
                          <th>Price</th>
                          <th>Image</th>
                          <th colaspan='2'>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          try {
                              $pdo =  new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
                              $pdo->setAttribute(
                                  PDO::ATTR_ERRMODE,
                                  PDO::ERRMODE_EXCEPTION
                              );
                          } catch (PDOException $e) {
                              die($e->getMessage());
                          }
                          $fetch = $pdo->prepare('select * from product');
                          $fetch->execute();
                          $result = $fetch->fetchAll();
                          foreach ($result as $r) {
                              if (!empty($r)) {
                                  ?>
                        <tr>
                          <td><?= $r['id']?></td>
                          <td><?= $r['name'] ?></td>
                          <td><?= $r['price'] ?></td>
                          <td><img src="<?= '/images/'.$r['image'] ?>"></td>
                          <td><input type="submit" name="edit" value="Edit"></td>
                          <td><input type="submit" name="delete" value="Delete"></td>
                        </tr>
                        <?php
                              } else {
                                  echo "No Record Found..";
                              }
                          }
                          ?>
                          <?php
                              if (isset($_POST['edit'])) {
                                  echo 'Edit';
                              }
                              if (isset($_POST['delete'])) {
                                  echo 'delete';
                              }
                          ?>
                      </tbody>
                        </form>
                    </table>
                  </div>
                </div>
              </div>
            </div>
<?php include 'footer.php'; ?>
