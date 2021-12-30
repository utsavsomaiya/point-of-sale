<?php
  session_start();
  include 'header.php';
  if (isset($_POST['submit'])) {
      try {
          $pdo =  new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
          $pdo->setAttribute(
              PDO::ATTR_ERRMODE,
              PDO::ERRMODE_EXCEPTION
          );
      } catch (PDOException $e) {
          die($e->getMessage());
      }
      if (!empty($_FILES['image']['name']) && !empty($_POST['pname']) && !empty($_POST['price'])) {
          $pname = $_POST['pname'];
          $price = $_POST['price'];
          $name = $_FILES['image']['name'];
          $fetch = $pdo->prepare("insert into product(name,price,image) values('$pname','$price','$name')");
          $result = $fetch->execute();
          if (isset($result)) {
              $a = "product.php";
              $_SESSION['msg'] = "Add Successfully";
          } else {
              echo 'No';
          }
      } else {
          echo "Please enter the data..";
      }
      header("location:$a");
  }
?>
<div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add new Product</h4>
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputUsername1">Product Name</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Product Name" name="pname">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Product Price</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Product Price" name="price">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Image</label>
                      <input type="file" class="form-control" accept="" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary me-2" name="submit">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
        </div>
</div>
<?php include 'footer.php'; ?>
