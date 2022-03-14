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
        for ($i = 0; $i < sizeof($discountTires); $i++) {
            $discountTierIds[$i] = $discountTires[$i]['tier_id'];
            $minimumSpendAmounts[$i] = $discountTires[$i]['minimum_spend_amount'];
            $discountDigits[$i] = $discountTires[$i]['discount_digit'];
        }
    }
    if (isset($_POST['submit'])) {
        if (empty($_POST['name'])) {
            $_SESSION['name_alert'] = "Please enter discount name.";
        }
        if (empty($_POST['type'])) {
            $_SESSION['type_alert'] = "Please select discount type.";
        }
        if (empty($_POST['status'])) {
            $_SESSION['status_alert'] = "Please select discount status.";
        }
        $discountName = $_POST['name'];
        $discountType = $_POST['type'];
        $discountStatus = $_POST['status'];
        for ($i = 0; $i < sizeof($_POST['digit']); $i++) {
            if (empty($_POST['digit'][$i])) {
                $_SESSION['digit_alert'][$i] = "Please enter digit.";
            }
            if (empty($_POST['minimum_spend_amount'][$i])) {
                $_SESSION['minimum_spend_amount_alert'][$i] = "Please enter minimum spend amount.";
            }
            if ($_POST['type'] == "1" && $_POST['digit'][$i] > 100) {
                $_SESSION['digit_alert'][$i] = "Percentage is not greater than 100.";
                $_SESSION['discount_name'] = $_POST['name'];
                $_SESSION['minimum_spend_amount'][$i] = $_POST['minimum_spend_amount'][$i];
                $_SESSION['digit'][$i] = $_POST['digit'][$i];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
                header("location:../discount/edit.php?id=$discountId");
                exit;
            }
        }
        $_SESSION['tier_digit'] = $_POST['digit'];
        $discountDigit = [];
        $minimumSpendAmount = [];

        for ($i = 0; $i < sizeof($_POST['digit']); $i++) {
            if (in_array($_POST['digit'][$i], $discountDigit)) {
                $_SESSION['digit_alert'][$i] = "Discount digits are same";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount'][$i] = $_POST['minimum_spend_amount'][$i];
                $_SESSION['digit'][$i] = $_POST['digit'][$i];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmount)) {
                $_SESSION['minimum_spend_amount_alert'][$i] = "Minimum spend amount are same";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount'][$i] = $_POST['minimum_spend_amount'][$i];
                $_SESSION['digit'][$i] = $_POST['digit'][$i];
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

        for ($i = 0; $i < sizeof($_POST['digit']); $i++) {
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

        if (sizeof($_POST['digit']) == sizeof($discountDigits)) {
            for ($i = 0; $i < sizeof($discountTierIds); $i++) {
                $updateDiscountTire = $pdo->prepare("UPDATE `discount_tier` SET `minimum_spend_amount` = :minimumAmount, `discount_digit` = :digit WHERE `tier_id` = :id");
                $updateDiscountTire->bindParam(':minimumAmount', $minimumSpendAmount[$i]);
                $updateDiscountTire->bindParam(':digit', $discountDigit[$i]);
                $updateDiscountTire->bindParam(':id', $discountTierIds[$i]);
                $updateDiscountTire->execute();
            }
        }

        if (sizeof($_POST['digit']) > sizeof($discountDigits)) {
            $differenceTierDigit =  array_diff($_POST['digit'], $discountDigits);
            $differenceTierMinimumSpendAmount = array_diff($_POST['minimum_spend_amount'], $minimumSpendAmounts);
            for ($i = 0; $i < sizeof($differenceTierDigit); $i++) {
                $insertDiscountTier = $pdo->prepare("INSERT INTO `discount_tier`(`discount_id`,`minimum_spend_amount`,`discount_digit`) VALUES(:discount_id, :minimum_spend_amount, :discount_digit)");
                $insertDiscountTier->bindParam(':discount_id', $discountId);
                $insertDiscountTier->bindParam(':minimum_spend_amount', $differenceTierMinimumSpendAmount[$i]);
                $insertDiscountTier->bindParam(':discount_digit', $differenceTierDigit[$i]);
                $isExecuted = $insertDiscountTier->execute();
            }
        }

        if (sizeof($_POST['digit']) < sizeof($discountDigits)) {
            $differenceTierDigit =  array_diff($discountDigits, $_POST['digit']);
            $differenceTierMinimumSpendAmount = array_diff($minimumSpendAmounts, $_POST['minimum_spend_amount']);
            for ($i = 0; $i < sizeof($differenceTierDigit); $i++) {
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
                                    placeholder="Discount Name" name="name" required
                                    <?php
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
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['type_alert'])) {
                                            echo $_SESSION['type_alert'];
                                            unset($_SESSION['type_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <div class="form-group" id="container">
                                <div>
                                    <button type="button" class="input-group-text bg-primary text-white" style="margin-left: 350px;" onclick="add()" id="add-button">
                                        Add new
                                    </button>
                                </div>
                                <div class="input-group" id="discount-tiers-container">
                                    <div class="input-group-append">
                                        <label for="minimum-amount">Minium Spend Amount</label>
                                        <input type="number" id="minimum-amount" class="form-control" required
                                        placeholder="Minium Spend Amount" name="minimum_spend_amount[]"
                                            <?php
                                                if (isset($_SESSION['minimum_spend_amount']) && isset($_SESSION['minimum_spend_amount'][0])) {
                                                    echo "value=\"".$_SESSION['minimum_spend_amount'][0]."\"";
                                                    unset($_SESSION['minimum_spend_amount'][0]);
                                                }
                                            ?>
                                            value="<?= $minimumSpendAmounts[0]?>"
                                        >
                                        <label class="text-danger">
                                            <?php
                                                if (isset($_SESSION['minimum_spend_amount_alert']) && isset($_SESSION['minimum_spend_amount_alert'][0])) {
                                                    echo $_SESSION['minimum_spend_amount_alert'][0];
                                                    unset($_SESSION['minimum_spend_amount_alert'][0]);
                                                }
                                            ?>
                                        </label>
                                    </div>
                                    <div class="input-group-append">
                                        <label for="discountDigit">Discount digit</label>
                                        <input type="number" id="discountDigit" class="form-control"
                                            placeholder="Discount digit" name="digit[]" required
                                            <?php
                                                if (isset($_SESSION['digit']) && isset($_SESSION['digit'][0])) {
                                                    echo "value=\"".$_SESSION['digit'][0]."\"";
                                                    unset($_SESSION['digit'][0]);
                                                }
                                            ?>
                                            value="<?= $discountDigits[0]?>"
                                        >
                                        <label class="text-danger">
                                            <?php
                                                if (isset($_SESSION['digit_alert']) && isset($_SESSION['digit_alert'][0])) {
                                                    echo $_SESSION['digit_alert'][0];
                                                    unset($_SESSION['digit_alert'][0]);
                                                }
                                            ?>
                                        </label>
                                    </div>
                                    <div class="input-group-append" style="display: none;"></div>
                                </div>
                                <?php for ($i = 1; $i < sizeof($discountTierIds); $i++) { ?>
                                    <div class="input-group" id="discount-tiers-container-<?= $i; ?>">
                                        <div class="input-group-append">
                                            <label for="minimum-amount">Minium Spend Amount</label>
                                            <input type="number" id="minimum-amount"  required class="form-control"
                                                placeholder="Minium Spend Amount" name="minimum_spend_amount[]"
                                                <?php
                                                    if (isset($_SESSION['minimum_spend_amount']) && isset($_SESSION['minimum_spend_amount'][$i])) {
                                                        echo "value=\"".$_SESSION['minimum_spend_amount'][$i]."\"";
                                                        unset($_SESSION['minimum_spend_amount'][$i]);
                                                    }
                                                ?>
                                                value="<?= $minimumSpendAmounts[$i]?>"
                                            >
                                            <label class="text-danger">
                                                <?php
                                                    if (isset($_SESSION['minimum_spend_amount_alert']) && isset($_SESSION['minimum_spend_amount_alert'][$i])) {
                                                        echo $_SESSION['minimum_spend_amount_alert'][$i];
                                                        unset($_SESSION['minimum_spend_amount_alert'][$i]);
                                                    }
                                                ?>
                                            </label>
                                        </div>
                                        <div class="input-group-append">
                                            <label for="discountDigit">Discount digit</label>
                                            <input type="number" id="discountDigit"  required class="form-control"
                                                placeholder="Discount digit" name="digit[]"
                                                <?php
                                                    if (isset($_SESSION['digit']) && isset($_SESSION['digit'][$i])) {
                                                        echo "value=\"".$_SESSION['digit'][$i]."\"";
                                                        unset($_SESSION['digit'][$i]);
                                                    }
                                                ?>
                                                value="<?= $discountDigits[$i]?>"
                                            >
                                            <label class="text-danger">
                                                <?php
                                                    if (isset($_SESSION['digit_alert']) && isset($_SESSION['digit_alert'][$i])) {
                                                        echo $_SESSION['digit_alert'][$i];
                                                        unset($_SESSION['digit_alert'][$i]);
                                                    }
                                                ?>
                                            </label>
                                        </div>
                                        <div class="input-group-append">
                                            <i class="fa fa-trash-o" onclick="remove(<?= $i ?>)"></i>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                if (isset($_SESSION['tier_digit'])) {
                                    for ($i = sizeof($discountTierIds); $i < sizeof($_SESSION['tier_digit']); $i++) { ?>
                                        <div class="input-group" id="discount-tiers-container-<?= $i; ?>">
                                            <div class="input-group-append">
                                                <label for="minimum-amount">Minium Spend Amount</label>
                                                <input type="number" id="minimum-amount" class="form-control" required
                                                    placeholder="Minium Spend Amount" name="minimum_spend_amount[]"
                                                    <?php
                                                        if (isset($_SESSION['minimum_spend_amount']) && isset($_SESSION['minimum_spend_amount'][$i])) {
                                                            echo "value=\"".$_SESSION['minimum_spend_amount'][$i]."\"";
                                                            unset($_SESSION['minimum_spend_amount'][$i]);
                                                        }
                                                    ?>
                                                >
                                                <label class="text-danger">
                                                    <?php
                                                        if (isset($_SESSION['minimum_spend_amount_alert']) && isset($_SESSION['minimum_spend_amount_alert'][$i])) {
                                                            echo $_SESSION['minimum_spend_amount_alert'][$i];
                                                            unset($_SESSION['minimum_spend_amount_alert'][$i]);
                                                        }
                                                    ?>
                                                </label>
                                            </div>
                                            <div class="input-group-append">
                                                <label for="discountDigit">Discount digit</label>
                                                <input type="number" id="discountDigit" required class="form-control"
                                                    placeholder="Discount digit" name="digit[]"
                                                    <?php
                                                        if (isset($_SESSION['digit']) && isset($_SESSION['digit'][$i])) {
                                                            echo "value=\"".$_SESSION['digit'][$i]."\"";
                                                            unset($_SESSION['digit'][$i]);
                                                        }
                                                    ?>
                                                >
                                                <label class="text-danger">
                                                    <?php
                                                        if (isset($_SESSION['digit_alert']) && isset($_SESSION['digit_alert'][$i])) {
                                                            echo $_SESSION['digit_alert'][$i];
                                                            unset($_SESSION['digit_alert'][$i]);
                                                        }
                                                    ?>
                                                </label>
                                            </div>
                                            <div class="input-group-append">
                                                <i class="fa fa-trash-o" onclick="remove(<?= $i ?>)"></i>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                                <?php unset($_SESSION['tier_digit']);?>
                            </div>
                            <div class="form-group">
                                <label for="discountStatus">Status</label>
                                <select id="discountStatus" class="form-control" name="status" >
                                    <option value="">--Select Status--</option>
                                    <option value="2"
                                    <?php
                                        if (isset($discountStatus)) {
                                            if ($discountStatus == "2") {
                                                echo 'selected="selected"';
                                            }
                                        }
                                    ?>
                                    >Active</option>
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
<script>
    var index = <?= sizeof($discountTierIds) ?>;
</script>
<script type="text/javascript" src="/admin/js/discount.js"></script>
<?php include '../layout/footer.php'; ?>