<?php
    session_start();
    include 'layout/header.php';
    require 'layout/db_connect.php';

    if (isset($_POST['submit'])) {
        if (is_numeric($_POST['discount'])) {
            $discount = $_POST['discount'];
            $fetch = $pdo->prepare("insert into discount(percentage) values('$discount')");
            $result = $fetch->execute();
            if (isset($result)) {
                $_SESSION['msg'] = "Discount apply successfully";
            }
        } else {
            $_SESSION['discountAlert'] = "Enter Only Numeric value";
        }
    }
?>
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-md-6 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Discount</h4>
						<form class="forms-sample" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="exampleInputUsername1">Discount</label>
								<input type="text" class="form-control" id="exampleInputUsername1"
									placeholder="Discount" name="discount" required>
								<label style="color:red;">
                                <?php
                                if (isset($_SESSION['discountAlert'])) {
                                    echo $_SESSION['discountAlert'];
                                    unset($_SESSION['discountAlert']);
                                }
                                ?>
                                </label>
							</div>
						<button type="submit" class="btn btn-primary me-2" name="submit">Submit</button>
<?php
     if (isset($_SESSION['msg'])) {
         ?>
		<div id="snackbar"> <?php echo $_SESSION['msg']; ?> </div>
<?php
     }
?>
<?php
    include 'layout/footer.php';
    unset($_SESSION['msg']);
?>