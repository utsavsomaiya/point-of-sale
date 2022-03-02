<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_POST['id'])) {
        $discountId = $_POST['id'];
        $discountStatus = $_POST['status'];
        $updateDiscount = $pdo->prepare("UPDATE `discount` SET `status`=:status WHERE `id` = :id ");
        $updateDiscount->bindParam(':status', $discountStatus);
        $updateDiscount->bindParam(':id', $discountId);
        $updateDiscount->execute();
    }
    if (isset($_GET['id'])) {
        $discountId = $_GET['id'];
        $fetchDiscount = $pdo->prepare("SELECT * FROM `discount` WHERE `id` = :id");
        $fetchDiscount->bindParam(':id', $discountId);
        $fetchDiscount->execute();
        $discounts = $fetchDiscount->fetchAll();
        foreach ($discounts as $discount) {
            $discountDigit = (int) $discount['digit'];
            $discountType = $discount['type'];
            $discountStatus = $discount['status'];
        }
    }
    if (isset($_POST['submit'])) {
        if (empty($_POST['digit'])) {
            $_SESSION['digit_alert'] = "Please enter data..";
        }
        if (empty($_POST['type'])) {
            $_SESSION['type_alert'] = "Please enter data..";
        }
        if (empty($_POST['status'])) {
            $_SESSION['status_alert'] = "Please enter data..";
        }
        if (empty($_POST['digit']) || empty($_POST['type']) || empty($_POST['status'])) {
            header("location:../discount/edit.php?id=$discountId");
            exit;
        }
        if ($_POST['digit'] > "100"  && $_POST['type'] == "1") {
            $_SESSION['digit_alert'] = "Percentage could not be greater than 100";
            header("location:../discount/edit.php?id=$discountId");
            exit;
        }
        $discountDigit = $_POST['digit'];
        $discountType = $_POST['type'];
        $discountStatus = $_POST['status'];
        $updateDiscount = $pdo->prepare("UPDATE `discount` SET `type`=:type, `digit`=:digit, `status`=:status WHERE `id` = :id ");
        $updateDiscount->bindParam(':type', $discountType);
        $updateDiscount->bindParam(':digit', $discountDigit);
        $updateDiscount->bindParam(':status', $discountStatus);
        $updateDiscount->bindParam(':id', $discountId);
        $isExecuted = $updateDiscount->execute();
        if ($isExecuted) {
            $_SESSION['msg'] = "Update Successfully";
            header('location:../discount/list.php');
            exit;
        } else {
            $_SESSION['msg'] = "Something went wrong";
            header("location:../discount/edit.php?id=$discountId");
            exit;
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
                        <h4 class="card-title">Edit Discount</h4>
                        <form class="forms-sample" method="post">
                            <div class="form-group">
                                <label for="discount-digit">Discount digit</label>
                                <input type="number" class="form-control" id="discount-digit" placeholder="Discount   digit" name="digit"
                                <?php
                                    if (isset($discountDigit)) {
                                        echo "value=\"".$discountDigit."\"";
                                    }
                                ?>
                                >
                                <label style="color:red;">
                                <?php
                                    if (isset($_SESSION['digit_alert'])) {
                                        echo $_SESSION['digit_alert'];
                                        unset($_SESSION['digit_alert']);
                                    }
                                ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="discountType">Type Of Discount</label>
                                <select id="discountType" class="form-control" name="type" required>
                                    <option value="">--Select Type--</option>
                                    <option value="1"
                                    <?php
                                        if (isset($discountType)) {
                                            if ($discountType == "1") {
                                                echo 'selected="selected"';
                                            }
                                        }
                                    ?>
                                    >%</option>
                                    <option value="2"
                                    <?php
                                        if (isset($discountType)) {
                                            if ($discountType == "2") {
                                                echo 'selected="selected"';
                                            }
                                        }
                                    ?>
                                    >$</option>
                                </select>
                                <label style="color:red;">
                                <?php
                                    if (isset($_SESSION['type_alert'])) {
                                        echo $_SESSION['type_alert'];
                                        unset($_SESSION['type_alert']);
                                    }
                                ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="discountStatus">Status</label>
                                <select id="discountStatus" class="form-control" name="status" >
                                    <option value="">--Select Status--</option>
                                    <option value="2" <?php if (isset($discountStatus)) {
                                    if ($discountStatus == "2") {
                                        echo 'selected="selected"';
                                    }
                                } ?>>Active</option>
                                    <option value="1"
                                    <?php
                                    if (isset($discountStatus)) {
                                        if ($discountStatus == "1") {
                                            echo 'selected="selected"';
                                        }
                                    }
                                    ?>
                                    >Inactive</option>
                                </select>
                                <label style="color:red;">
                                    <?php
                                        if (isset($_SESSION['status_alert'])) {
                                            echo $_SESSION['status_alert'];
                                            unset($_SESSION['status_alert']);
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
</div>
<?php include '../layout/footer.php'; ?>