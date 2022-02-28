<?php
  session_start();
  if (isset($_POST['submit'])) {
      require '../layout/db_connect.php';
      if (!empty($_POST['digit']) && !empty($_POST['type']) && !empty($_POST['status'])) {
          if ($_POST['digit'] > 100  && $_POST['type'] == "1") {
              $_SESSION['digit_alert'] = "Percentage could not be greater than 100";
              header('location:../discount/add.php');
          } else {
              $digit = $_POST['digit'];
              $type = $_POST['type'];
              $fetch = $pdo->prepare('SELECT `type`,`digit` FROM `discount` WHERE `type` = :type AND `digit` = :digit LIMIT 1');
              $fetch->bindParam(':type', $type);
              $fetch->bindParam(':digit', $digit);
              $fetch->execute();
              $count = $fetch->rowCount();
              if ($count == 1) {
                  $_SESSION['digit_alert'] = "Already taken this discount";
                  header('location:../discount/add.php');
                  exit();
              }
              $status = $_POST['status'];
              $fetch = $pdo->prepare("INSERT INTO `discount`(`type`,`digit`,`status`) VALUES( :type, :digit, :status)");
              $fetch->bindParam(':type', $type);
              $fetch->bindParam(':digit', $digit);
              $fetch->bindParam(':status', $status);
              $result = $fetch->execute();
              if (isset($result)) {
                  $_SESSION['msg'] = "Add Successfully";
                  header('location:../discount/list.php');
              } else {
                  $_SESSION['msg'] = "Not Successfully";
                  header('location:../discount/add.php');
              }
          }
      }
      if (empty($_POST['digit'])) {
          $_SESSION['digit_alert'] = "Please enter data..";
          header('location:../discount/add.php');
      }
      if (empty($_POST['type'])) {
          $_SESSION['type_alert'] = "Please enter data..";
          header('location:../discount/add.php');
      }
      if (empty($_POST['status'])) {
          $_SESSION['status_alert'] = "Please enter data..";
          header('location:../discount/add.php');
      }
  }
?>
<?php include '../layout/header.php'; ?>
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-md-6 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Add new Discount</h4>
						<form class="forms-sample" method="post">
							<div class="form-group">
								<label for="discountDigit">Discount digit</label>
								<input type="number" class="form-control" id="discountDigit"
									placeholder="Discount digit" name="digit"
                                    <?php
                                    if (isset($_POST['digit'])) {
                                        echo "value=\"".$_POST['digit']."\"";
                                    }?>
                                required>
                                <label style="color:red;">
                                <?php
                                if (isset($_SESSION['digit_alert'])) {
                                    echo $_SESSION['digit_alert'];
                                }
                                ?>
                                </label>
							</div>
                            <div class="form-group">
                                <label for="discountType">Type Of Discount</label>
                                <select id="discountType" class="form-control" name="type" required>
                                    <option value="">--Select Type--</option>
                                    <option value="1" <?php if (isset($_SESSION['type'])) {
                                    if ($_SESSION['type'] == "1") {
                                        echo 'selected="selected"';
                                    }
                                } ?>>%</option>
                                    <option value="2" <?php if (isset($_SESSION['type'])) {
                                    if ($_SESSION['type'] == "2") {
                                        echo 'selected="selected"';
                                    }
                                } ?>>$</option>
                                </select>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['type_alert'])) {
                                        echo $_SESSION['type_alert'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="discountStatus">Status</label>
                                <select id="discountStatus" class="form-control" name="status" required>
                                    <option value="">--Select Status--</option>
                                    <option value="2" <?php if (isset($_SESSION['status'])) {
                                        if ($_SESSION['type'] == "2") {
                                            echo 'selected="selected"';
                                        }
                                    } ?>>Active</option>
                                    <option value="1" <?php if (isset($_SESSION['status'])) {
                                        if ($_SESSION['type'] == "1") {
                                            echo 'selected="selected"';
                                        }
                                    } ?>>Inactive</option>
                                </select>
                                <label style="color:red;">
                                    <?php
                                    if (isset($_SESSION['status_alert'])) {
                                        echo $_SESSION['status_alert'];
                                    }
                                    ?>
                                </label>
                            </div>
							<button type="submit" class="btn btn-primary me-2" name="submit">Submit</button>
							<a href="../discount/list.php" class="btn btn-light">Cancel</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include '../layout/footer.php'; ?>