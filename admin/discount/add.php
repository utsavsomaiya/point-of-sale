<?php
    session_start();
    if (isset($_POST['submit'])) {
        if (empty($_POST['name'])) {
            $_SESSION['name_alert'] = "Please enter data..";
        }
        if (empty($_POST['digit'])) {
            $_SESSION['digit_alert'] = "Please enter data..";
        }
        if (empty($_POST['type'])) {
            $_SESSION['type_alert'] = "Please enter data..";
        }
        if (empty($_POST['status'])) {
            $_SESSION['status_alert'] = "Please enter data..";
        }
        if (empty($_POST['name']) || empty($_POST['digit']) || empty($_POST['type']) || empty($_POST['status'])) {
            header('location:../discount/add.php');
            exit;
        }
        if ($_POST['digit'] > "100"  && $_POST['type'] == "1") {
            $_SESSION['digit_alert'] = "Percentage could not be greater than 100";
            header('location:../discount/add.php');
            exit;
        }
        require '../layout/db_connect.php';
        $discountName =$_POST['name'];
        $_SESSION['discount_name'] = $discountName;
        $discountDigit = $_POST['digit'];
        $_SESSION['digit'] = $discountDigit;
        $discountType = $_POST['type'];
        $_SESSION['type'] = $discountType;
        $fetchDiscount = $pdo->prepare('SELECT `type`,`digit` FROM `discount` WHERE `type` = :type AND `digit` = :digit LIMIT 1');
        $fetchDiscount->bindParam(':type', $discountType);
        $fetchDiscount->bindParam(':digit', $discountDigit);
        $fetchDiscount->execute();
        $count = $fetchDiscount->rowCount();
        if ($count == 1) {
            $_SESSION['digit_alert'] = "Already taken this discount";
            header('location:../discount/add.php');
            exit;
        }
        $discountStatus = $_POST['status'];
        $_SESSION['status'] = $discountStatus;
        $insertDiscount = $pdo->prepare("INSERT INTO `discount`(`name`,`type`,`digit`,`status`) VALUES(:name, :type, :digit, :status)");
        $insertDiscount->bindParam(':name', $discountName);
        $insertDiscount->bindParam(':type', $discountType);
        $insertDiscount->bindParam(':digit', $discountDigit);
        $insertDiscount->bindParam(':status', $discountStatus);
        $isExecute = $insertDiscount->execute();
        if ($isExecute) {
            $_SESSION['msg'] = "Add Successfully";
            header('location:../discount/list.php');
            exit;
        }
        $_SESSION['msg'] = "Something went wrong";
        header('location:../discount/add.php');
        exit;
    }
?>
<?php include '../layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            Add new Discount
                        </h4>
                        <form class="forms-sample" method="post">
                            <div class="form-group">
                                <label for="discountName">Discount Name</label>
                                <input type="text" class="form-control" id="discountName"
                                    placeholder="Discount Name" name="name" required
                                    <?php
                                        if (isset($_SESSION['discount_name'])) {
                                            echo "value=\"".$_SESSION['discount_name']."\"";
                                            unset($_SESSION['discount_name']);
                                        }
                                    ?>
                                >
                                <label style="color:red;">
                                    <?php
                                        if (isset($_SESSION['name_alert'])) {
                                            echo $_SESSION['name_alert'];
                                            unset($_SESSION['name_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="discountDigit">Discount digit</label>
                                <input type="number" class="form-control" id="discountDigit"
                                    placeholder="Discount digit" name="digit" required
                                    <?php
                                        if (isset($_SESSION['digit'])) {
                                            echo "value=\"".$_SESSION['digit']."\"";
                                            unset($_SESSION['digit']);
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
                                        if (isset($_SESSION['type'])) {
                                            if ($_SESSION['type'] == "1") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['type']);
                                            }
                                        }
                                    ?>
                                    >%</option>
                                    <option value="2"
                                    <?php
                                        if (isset($_SESSION['type'])) {
                                            if ($_SESSION['type'] == "2") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['type']);
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
                                <select id="discountStatus" class="form-control" name="status" required>
                                    <option value="">--Select Status--</option>
                                    <option value="2"
                                    <?php
                                        if (isset($_SESSION['status'])) {
                                            if ($_SESSION['status'] == "2") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['status']);
                                            }
                                        }
                                    ?>
                                    >Active</option>
                                    <option value="1"
                                    <?php
                                        if (isset($_SESSION['status'])) {
                                            if ($_SESSION['status'] == "1") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['type']);
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