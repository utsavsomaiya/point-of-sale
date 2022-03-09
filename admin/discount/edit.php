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
            $discountName = $discount['name'];
            $minimumSpendAmount = $discount['minimum_spend_amount'];
            $discountType = $discount['type'];
            $discountStatus = $discount['status'];
        }
    }
    if (isset($_POST['submit'])) {
        if (empty($_POST['name'])) {
            $_SESSION['name_alert'] = "Please enter discount name.";
        }
        if (empty($_POST['minimum_spend_amount'])) {
            $_SESSION['minimum_spend_amount_alert'] = "Please enter minimum spend amount.";
        }
        if (empty($_POST['digit'])) {
            $_SESSION['digit_alert'] = "Please enter discount digit.";
        }
        if (empty($_POST['type'])) {
            $_SESSION['type_alert'] = "Please select discount type.";
        }
        if (empty($_POST['status'])) {
            $_SESSION['status_alert'] = "Please select discount status.";
        }
        if (empty($_POST['name']) || empty($_POST['digit']) || empty($_POST['type']) || empty($_POST['status']) || empty($_POST['minimum_spend_amount'])) {
            header("location:../discount/edit.php?id=$discountId");
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
            header("location:../discount/edit.php?id=$discountId");
            exit;
        }

        $updateDiscount = $pdo->prepare("UPDATE `discount` SET `name`=:name, `minimum_spend_amount`=:minimumAmount, `type`=:type, `digit`=:digit, `status`=:status WHERE `id` = :id ");
        $updateDiscount->bindParam(':name', $discountName);
        $updateDiscount->bindParam(':minimumAmount', $minimumSpendAmount);
        $updateDiscount->bindParam(':type', $discountType);
        $updateDiscount->bindParam(':digit', $discountDigit);
        $updateDiscount->bindParam(':status', $discountStatus);
        $updateDiscount->bindParam(':id', $discountId);
        $isExecuted = $updateDiscount->execute();
        if ($isExecuted) {
            $_SESSION['message'] = "Discount updated successfully.";
            header('location:../discount/list.php');
            exit;
        }
        $_SESSION['msg'] = "Something went wrong";
        $_SESSION['discount_name'] = $discountName;
        $_SESSION['minimum_spend_amount'] =$minimumSpendAmount;
        $_SESSION['digit'] = $discountDigit;
        $_SESSION['type'] = $discountType;
        $_SESSION['status'] = $discountStatus;
        header("location:../discount/edit.php?id=$discountId");
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
                        <h4 class="card-title">Edit Discount</h4>
                        <form class="forms-sample" method="post">
                            <div class="form-group">
                                <label for="discount-name">Discount Name</label>
                                <input type="text" class="form-control" id="discount-name" placeholder="Discount   Name" name="name"
                                <?php
                                    if (isset($_SESSION['discount_name'])) {
                                        echo "value=\"".$_SESSION['discount_name']."\"";
                                        unset($_SESSION['discount_name']);
                                    }
                                    if (isset($discountName)) {
                                        echo "value=\"".$discountName."\"";
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
                                <input type="number" class="form-control" id="minimum-amount" placeholder="Minium Spend Amount" name="minimum_spend_amount"
                                <?php
                                    if (isset($_SESSION['minimum_spend_amount'])) {
                                        echo "value=\"".$_SESSION['minimum_spend_amount']."\"";
                                        unset($_SESSION['minimum_spend_amount']);
                                    }
                                    if (isset($minimumSpendAmount)) {
                                        echo "value=\"".$minimumSpendAmount."\"";
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
                                <label for="discount-digit">Discount digit</label>
                                <input type="number" class="form-control" id="discount-digit" placeholder="Discount   digit" name="digit"
                                <?php
                                    if (isset($_SESSION['digit'])) {
                                        echo "value=\"".$_SESSION['digit']."\"";
                                        unset($_SESSION['digit']);
                                    }
                                    if (isset($discountDigit)) {
                                        echo "value=\"".$discountDigit."\"";
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
                                        if (isset($discountType)) {
                                            if ($discountType == "1") {
                                                echo 'selected="selected"';
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
                                        if (isset($discountType)) {
                                            if ($discountType == "2") {
                                                echo 'selected="selected"';
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
                                <select id="discountStatus" class="form-control" name="status" >
                                    <option value="">--Select Status--</option>
                                    <option value="2"
                                    <?php
                                        if (isset($_SESSION['status'])) {
                                            if ($_SESSION['status'] == "2") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['status']);
                                            }
                                        }
                                        if (isset($discountStatus)) {
                                            if ($discountStatus == "2") {
                                                echo 'selected="selected"';
                                            }
                                        }
                                    ?>
                                    >Active</option>
                                    <option value="1"
                                    <?php
                                        if (isset($_SESSION['status'])) {
                                            if ($_SESSION['status'] == "1") {
                                                echo 'selected="selected"';
                                                unset($_SESSION['status']);
                                            }
                                        }
                                        if (isset($discountStatus)) {
                                            if ($discountStatus == "1") {
                                                echo 'selected="selected"';
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