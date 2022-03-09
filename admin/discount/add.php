<?php
    session_start();
    if (isset($_POST['submit'])) {
        if (empty($_POST['name'])) {
            $_SESSION['name_alert'] = "Please enter data..";
        }
        if (empty($_POST['minimum_spend_amount'])) {
            $_SESSION['minimum_spend_amount_alert'] = "Please enter minimum spend amount.";
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
        if (empty($_POST['name']) || empty($_POST['digit']) || empty($_POST['type']) || empty($_POST['status'])  || empty($_POST['minimum_spend_amount'])) {
            header('location:../discount/add.php');
            exit;
        }

        $discountName = $_POST['name'];
        $minimumSpendAmount = $_POST['minimum_spend_amount'];
        $discountDigit = $_POST['digit'];
        $discountType = $_POST['type'];
        $discountStatus = $_POST['status'];

        if ($_POST['digit'] > "100"  && $_POST['type'] == "1") {
            $_SESSION['digit_alert'] = "Percentage could not be greater than 100";
            $_SESSION['discount_name'] = $discountName;
            $_SESSION['minimum_spend_amount'] =$minimumSpendAmount;
            $_SESSION['digit'] = $discountDigit;
            $_SESSION['type'] = $discountType;
            $_SESSION['status'] = $discountStatus;
            header('location:../discount/add.php');
            exit;
        }
        require '../layout/db_connect.php';

        $fetchDiscount = $pdo->prepare('SELECT `type`,`digit` FROM `discount` WHERE `type` = :type AND `digit` = :digit LIMIT 1');
        $fetchDiscount->bindParam(':type', $discountType);
        $fetchDiscount->bindParam(':digit', $discountDigit);
        $fetchDiscount->execute();
        $count = $fetchDiscount->rowCount();
        if ($count == 1) {
            $_SESSION['digit_alert'] = "Already taken this discount";
            $_SESSION['discount_name'] = $discountName;
            $_SESSION['minimum_spend_amount'] =$minimumSpendAmount;
            $_SESSION['digit'] = $discountDigit;
            $_SESSION['type'] = $discountType;
            $_SESSION['status'] = $discountStatus;
            header('location:../discount/add.php');
            exit;
        }

        $insertDiscount = $pdo->prepare("INSERT INTO `discount`(`name`,`minimum_spend_amount`,`type`,`digit`,`status`) VALUES(:name, :minimumAmount, :type, :digit, :status)");
        $insertDiscount->bindParam(':name', $discountName);
        $insertDiscount->bindParam(':minimumAmount', $minimumSpendAmount);
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
        $_SESSION['discount_name'] = $discountName;
        $_SESSION['minimum_spend_amount'] =$minimumSpendAmount;
        $_SESSION['digit'] = $discountDigit;
        $_SESSION['type'] = $discountType;
        $_SESSION['status'] = $discountStatus;
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
                        <h4 class="card-title">Add new Discount</h4>
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
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['name_alert'])) {
                                            echo $_SESSION['name_alert'];
                                            unset($_SESSION['name_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="minimum-amount">Minium Spend Amount</label>
                                <input type="number" class="form-control" id="minimum-amount"
                                    placeholder="Minium Spend Amount" name="minimum_spend_amount" required
                                    <?php
                                        if (isset($_SESSION['minimum_spend_amount'])) {
                                            echo "value=\"".$_SESSION['minimum_spend_amount']."\"";
                                            unset($_SESSION['minimum_spend_amount']);
                                        }
                                    ?>
                                >
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['minimum_spend_amount_alert'])) {
                                            echo $_SESSION['minimum_spend_amount_alert'];
                                            unset($_SESSION['minimum_spend_amount_alert']);
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
                                <label class="text-danger">
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
                                <label class="text-danger">
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
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['status_alert'])) {
                                            echo $_SESSION['status_alert'];
                                            unset($_SESSION['status_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit">
                                Submit
                            </button>
                            <a href="../discount/list.php" class="btn btn-light">
                                Cancel
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layout/footer.php'; ?>