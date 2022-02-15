<?php
session_start();
require 'layout/db_connect.php';
$fetch = $pdo->prepare('SELECT `type`,`digit` FROM `discount` WHERE `id` = 1');
$fetch->execute();
$result = $fetch->fetchAll();
if (isset($_POST['submit'])) {
    if (!empty($_POST['discountType'])) {
        if ($_POST['discountType'] == "1") {
            if (!empty($_POST['discount']) && $_POST['discount'] <= 100) {
                $discount=$_POST['discount'];
                if ($result) {
                    $fetch = $pdo->prepare("UPDATE `discount` SET `type` = :discountType, `digit` = :discount WHERE `id` = 1");
                    $fetch->bindParam(':discountType', $_POST['discountType']);
                    $fetch->bindParam(':discount', $discount);
                    $result = $fetch->execute();
                    if (isset($result)) {
                        $_SESSION['msg'] = "Discount apply successfully";
                        header('location:/admin/settings.php');
                    }
                } else {
                    $fetch = $pdo->prepare("INSERT INTO `discount` (`id`,`type`,`digit`) VALUES('1',:discountType,:discount)");
                    $fetch->bindParam(':discountType', $_POST['discountType']);
                    $fetch->bindParam(':discount', $discount);
                    $result = $fetch->execute();
                    if (isset($result)) {
                        $_SESSION['msg'] = "Discount apply successfully";
                        header('location:/admin/settings.php');
                    }
                }
            } else {
                if (empty($_POST['discount'])) {
                    $_SESSION['discountAlert'] = "Enter the Discount";
                    header('location:/admin/settings.php');
                }
                if ($_POST['discount'] > 100) {
                    $_SESSION['discountAlert'] = "Discount must between 0 to 100";
                    header('location:/admin/settings.php');
                }
            }
        } else {
            if ($result) {
                $discount=$_POST['discount'];
                $fetch = $pdo->prepare("UPDATE `discount` SET `type` = :discountType, `digit` = :discount WHERE `id` = 1");
                $fetch->bindParam(':discountType', $_POST['discountType']);
                $fetch->bindParam(':discount', $discount);
                $result = $fetch->execute();
                if (isset($result)) {
                    $_SESSION['msg'] = "Discount apply successfully";
                    header('location:/admin/settings.php');
                } else {
                    $fetch = $pdo->prepare("INSERT INTO `discount` (`id`,`type`,`digit`) VALUES('1',:discountType,:discount)");
                    $fetch->bindParam(':discountType', $_POST['discountType']);
                    $fetch->bindParam(':discount', $discount);
                    $result = $fetch->execute();
                    if (isset($result)) {
                        $_SESSION['msg'] = "Discount apply successfully";
                        header('location:/admin/settings.php');
                    }
                }
            } else {
                if (empty($_POST['discount'])) {
                    $_SESSION['discountAlert'] = "Enter the Discount";
                    header('location:/admin/settings.php');
                }
            }
        }
    } else {
        $_SESSION['discountTypeAlert'] = "Please Select the type";
        header('location:/admin/settings.php');
    }
}
foreach ($result as $discount) {
    if (!empty($discount)) {
        $discountDigit = $discount['digit'];
        $discountType = $discount['type'];
    }
}
?>
<?php  include 'layout/header.php';?>
<div class="main-panel">
    <div class="content-wrapper">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Discount</h4>
                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group" style="margin-right:1.5px;">
                                    <label for="discount">Discount</label>
                                    <input id="discount" type="number" class="form-control" placeholder="Discount"
                                        name="discount" value="<?= $discountDigit ?>" required style="width:50%">
                                    <label style="color:red;">
                                        <?php
                                    if (isset($_SESSION['discountAlert'])) {
                                        echo $_SESSION['discountAlert'];
                                    }
                                    ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Discount type</label>
                                    <select name="discountType" class="form-control" required>
                                        <option value="">--Select Discount Type--</option>
                                        <option value="1"
                                        <?php
                                            if ($discountType == 1) {
                                                echo 'selected="selected"';
                                            }
                                        ?>>%</option>
                                        <option value="2" <?php
                                            if ($discountType == 2) {
                                                echo 'selected="selected"';
                                            }
                                        ?>>$</option>
                                    </select>
                                    <label style="color:red;">
                                        <?php
                                    if (isset($_SESSION['discountTypeAlert'])) {
                                        echo $_SESSION['discountTypeAlert'];
                                    }
                                    ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit">Submit</button>
                            <?php
                            include 'layout/footer.php';
                            ?>