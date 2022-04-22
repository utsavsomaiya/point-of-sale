<?php
    session_start();
    require '../layout/db_connect.php';
    $minimumSpendAmounts = [];
    $discountDigits = [];
    $discountTierIds = [];
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
            $discountName = $discount['name'];
            $discountType = $discount['type'];
            $discountStatus = $discount['status'];
        }
        $fetchDiscountTier = $pdo->prepare("SELECT * FROM `discount_tier` WHERE discount_id = :id");
        $fetchDiscountTier->bindParam(':id', $discountId);
        $fetchDiscountTier->execute();
        $discountTires = $fetchDiscountTier->fetchAll();
        for ($i = 0; $i < count($discountTires); $i++) {
            $discountTierIds[$i] = $discountTires[$i]['tier_id'];
            $minimumSpendAmounts[$i] = $discountTires[$i]['minimum_spend_amount'];
            $discountDigits[$i] = $discountTires[$i]['discount_digit'];
        }
    }
    if (isset($_POST['submit'])) {
        if (empty($_POST['name'])) {
            $_SESSION['name_alert'] = "Please enter discount name.";
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit'] = $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (empty($_POST['type'])) {
            $_SESSION['type_alert'] = "Please select discount type.";
            $_SESSION['discount_name'] = $_POST['name'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit'] = $_POST['digit'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (empty($_POST['status'])) {
            $_SESSION['status_alert'] = "Please select discount status.";
            $_SESSION['discount_name'] = $_POST['name'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit'] = $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
        }
        $discountName = $_POST['name'];
        $discountType = $_POST['type'];
        $discountStatus = $_POST['status'];
        for ($i = 0; $i < count($_POST['digit']); $i++) {
            if (empty($_POST['digit'][$i])) {
                $_SESSION['digit_alert'][$i] = "Please enter digit.";
                $_SESSION['discount_name'] = $_POST['name'];
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (empty($_POST['minimum_spend_amount'][$i])) {
                $_SESSION['minimum_spend_amount_alert'][$i] = "Please enter minimum spend amount.";
                $_SESSION['discount_name'] = $_POST['name'];
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if ($_POST['type'] == "1" && $_POST['digit'][$i] > 100) {
                $_SESSION['digit_alert'][$i] = "Percentage is not greater than 100.";
                $_SESSION['discount_name'] = $_POST['name'];
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
                header("location:../discount/edit.php?id=$discountId");
                exit;
            }
        }
        $discountDigit = [];
        $minimumSpendAmount = [];

        for ($i = 0; $i < count($_POST['digit']); $i++) {
            if (in_array($_POST['digit'][$i], $discountDigit)) {
                $_SESSION['digit_alert'][$i] = "Discount digits are same";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmount)) {
                $_SESSION['minimum_spend_amount_alert'][$i] = "Minimum spend amount are same";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmount) || in_array($_POST['digit'][$i], $discountDigit)) {
                header("location:../discount/edit.php?id=$discountId");
                exit;
            }
            $discountDigit[$i] = $_POST['digit'][$i];
            $minimumSpendAmount[$i] = $_POST['minimum_spend_amount'][$i];
        }

        for ($i = 0; $i < count($_POST['digit']); $i++) {
            if (empty($_POST['minimum_spend_amount'][$i]) || empty($_POST['digit'][$i])) {
                header("location:../discount/edit.php?id=$discountId");
                exit;
            }
            if ($_POST['type'] == "1" && $_POST['digit'][$i] > 100) {
                header("location:../discount/edit.php?id=$discountId");
                exit;
            }
        }
        if (empty($_POST['name']) || empty($_POST['type']) || empty($_POST['status'])) {
            header("location:../discount/edit.php?id=$discountId");
            exit;
        }


        $updateDiscount = $pdo->prepare("UPDATE `discount` SET `name` = :name, `type` = :type, status = :status WHERE `id`= :id ");
        $updateDiscount->bindParam(':name', $discountName);
        $updateDiscount->bindParam(':type', $discountType);
        $updateDiscount->bindParam(':id', $discountId);
        $updateDiscount->bindParam(':status', $discountStatus);
        $isExecuted = $updateDiscount->execute();

        if (count($_POST['digit']) == count($discountDigits)) {
            for ($i = 0; $i < count($discountTierIds); $i++) {
                $updateDiscountTire = $pdo->prepare("UPDATE `discount_tier` SET `minimum_spend_amount` = :minimumAmount, `discount_digit` = :digit WHERE `tier_id` = :id");
                $updateDiscountTire->bindParam(':minimumAmount', $minimumSpendAmount[$i]);
                $updateDiscountTire->bindParam(':digit', $discountDigit[$i]);
                $updateDiscountTire->bindParam(':id', $discountTierIds[$i]);
                $updateDiscountTire->execute();
            }
        }

        if (count($_POST['digit']) > count($discountDigits)) {
            $differenceTierDigit =  array_values(array_diff($_POST['digit'], $discountDigits));
            $differenceTierMinimumSpendAmount = array_values(array_diff($_POST['minimum_spend_amount'], $minimumSpendAmounts));
            for ($i = 0; ($i < count($differenceTierMinimumSpendAmount)) || ($i < count($differenceTierDigit)); $i++) {
                $insertDiscountTier = $pdo->prepare("INSERT INTO `discount_tier`(`discount_id`,`minimum_spend_amount`,`discount_digit`) VALUES(:discount_id, :minimum_spend_amount, :discount_digit)");
                $insertDiscountTier->bindParam(':discount_id', $discountId);
                $insertDiscountTier->bindParam(':minimum_spend_amount', $differenceTierMinimumSpendAmount[$i]);
                $insertDiscountTier->bindParam(':discount_digit', $differenceTierDigit[$i]);
                $isExecuted = $insertDiscountTier->execute();
            }
        }

        if (count($_POST['digit']) < count($discountDigits)) {
            $differenceTierDigit =  array_diff($discountDigits, $_POST['digit']);
            $differenceTierMinimumSpendAmount = array_diff($minimumSpendAmounts, $_POST['minimum_spend_amount']);
            for ($i = 0; $i < count($differenceTierDigit); $i++) {
                $deleteDiscountTier = $pdo->prepare("DELETE FROM `discount_tier` WHERE `minimum_spend_amount` = :minimumSpendAmount AND `discount_digit` = :discountDigit AND `discount_id` = :discount_id");
                $deleteDiscountTier->bindParam(':discount_id', $discountId);
                $deleteDiscountTier->bindParam(':minimumSpendAmount', $differenceTierMinimumSpendAmount[$i]);
                $deleteDiscountTier->bindParam(':discountDigit', $differenceTierDigit[$i]);
                $isExecuted = $deleteDiscountTier->execute();
            }
        }

        if ($isExecuted) {
            $_SESSION['message'] = "Discount updated successfully.";
            header('location:../discount/list.php');
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
                                <label for="discount-name">Discount Name</label>
                                <input type="text" class="form-control" id="discount-name"
                                    placeholder="Discount Name" name="name"
                                    <?php
                                        if (isset($_SESSION['discount_name'])) {
                                            echo "value=\"".$_SESSION['discount_name']."\"";
                                            unset($_SESSION['discount_name']);
                                        } else {
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
                                <label for="discountType">Type Of Discount</label>
                                <select id="discountType" class="form-control" name="type" >
                                    <option value="">--Select Type--</option>
                                    <option value="1"
                                    <?php
                                    if (isset($_SESSION['type']) && $_SESSION['type'] == "1") {
                                        echo 'selected="selected"';
                                        unset($_SESSION['type']);
                                    } elseif ($discountType == "1") {
                                        echo 'selected="selected"';
                                    }
                                    ?>
                                    >%</option>
                                    <option value="2"
                                    <?php
                                        if (isset($_SESSION['type']) && $_SESSION['type'] == "2") {
                                            echo 'selected="selected"';
                                            unset($_SESSION['type']);
                                        } elseif ($discountType == "2") {
                                            echo 'selected="selected"';
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
                            <h6><u><b>Minimum Spends</b></u></h6>
                            <div class="form-group" id="container">
                                <div>
                                    <button type="button" class="input-group-text bg-primary text-white" style="margin-left: 350px;" onclick="editPageAddMinimumRow()" id="add-button">
                                        Add new
                                    </button>
                                </div>
                                <div class="minimum-spend-row-container">
                                    <!-- Here added Minimum Spends Row using javascript -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="discountStatus">Status</label>
                                <select id="discountStatus" class="form-control" name="status" >
                                    <option value="">--Select Status--</option>
                                    <option value="2"
                                    <?php
                                        if (isset($_SESSION['status']) && $_SESSION['status'] == "2") {
                                            echo 'selected="selected"';
                                            unset($_SESSION['status']);
                                        } elseif ($discountStatus == "2") {
                                            echo 'selected="selected"';
                                        }
                                    ?>
                                    >Active</option>
                                    <option value="1"
                                    <?php
                                        if (isset($_SESSION['status']) && $_SESSION['status'] == "1") {
                                            echo 'selected="selected"';
                                            unset($_SESSION['status']);
                                        } elseif ($discountStatus == "1") {
                                            echo 'selected="selected"';
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
                                Update
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
<?php include '../discount/tier_template.php'; ?>
<script>
    var errorDiscountDigit = [];
    var errorMinimumSpendAmount = [];
    var digitAlert = [];
    var minimumSpendAlert = [];
    <?php if (isset($_SESSION['digit'])) { ?>
        var errorDiscountDigit = <?= json_encode($_SESSION['digit']); ?>;
        <?php } ?>
        <?php if (isset($_SESSION['minimum_spend_amount'])) { ?>
            var errorMinimumSpendAmount = <?= json_encode($_SESSION['minimum_spend_amount']); ?>;
            <?php unset($_SESSION['minimum_spend_amount']); ?>
            <?php } ?>
    <?php if (isset($_SESSION['digit_alert'])) { ?>
        var digitAlert = <?= json_encode($_SESSION['digit_alert']); ?>;
        <?php unset($_SESSION['digit_alert']); ?>
    <?php } ?>
    <?php if (isset($_SESSION['minimum_spend_amount_alert'])) { ?>
        var minimumSpendAlert = <?= json_encode($_SESSION['minimum_spend_amount_alert']); ?>;
        <?php unset($_SESSION['minimum_spend_amount_alert']); ?>
    <?php } ?>
</script>
<script type="text/javascript" src="/admin/js/discount.js"></script>
<script>
    var totalDiscounts = <?= count($discountTierIds) ?>;
    const discountDigits = <?= json_encode($discountDigits) ?>;
    const minimumSpendAmounts = <?= json_encode($minimumSpendAmounts);?>;
    <?php unset($_SESSION['digit']);?>
</script>
<script>availableEditPageOption();</script>
<?php include '../layout/footer.php'; ?>