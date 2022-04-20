<?php
    session_start();
    if (isset($_POST['submit'])) {
        if (empty($_POST['name'])) {
            $_SESSION['name_alert'] = "Please enter discount name.";
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit']= $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (empty($_POST['type'])) {
            $_SESSION['type_alert'] = "Please select discount type.";
            $_SESSION['discount_name'] = $_POST['name'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit']= $_POST['digit'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (empty($_POST['status'])) {
            $_SESSION['status_alert'] = "Please select discount status.";
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit']= $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['discount_name'] = $_POST['name'];
        }

        require '../layout/db_connect.php';

        $discountName = $_POST['name'];
        $fetchDiscount = $pdo->prepare('SELECT COUNT(*) AS discount_name FROM `discount` WHERE `name` = :discount_name ');
        $fetchDiscount->bindParam(':discount_name', $discountName);
        $fetchDiscount->execute();
        $discounts = $fetchDiscount->fetchAll();

        for ($i = 0; $i < count($_POST['digit']); $i++) {
            if (empty($_POST['digit'][$i])) {
                $_SESSION['digit_alert'][$i] = "Please enter digit.";
                $_SESSION['discount_name'] = $_POST['name'];
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit']= $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (empty($_POST['minimum_spend_amount'][$i])) {
                $_SESSION['minimum_spend_amount_alert'][$i] = "Please enter minimum spend amount.";
                $_SESSION['discount_name'] = $_POST['name'];
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit']= $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }

            if ($_POST['type'] == "1" && $_POST['digit'][$i] > 100) {
                $_SESSION['digit_alert'][$i] = "Percentage is not greater than 100.";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (((int) $discounts[0]['discount_name']) > 0) {
                $_SESSION['name_alert'] = "Already taken this name";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit']= $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
        }

        $discountDigits = [];
        $minimumSpendAmounts = [];
        for ($i = 0; $i < count($_POST['digit']); $i++) {
            if (in_array($_POST['digit'][$i], $discountDigits)) {
                $_SESSION['digit_alert'][$i] = "Discount digits are same";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount']= $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmounts)) {
                $_SESSION['minimum_spend_amount_alert'][$i] = "Minimum spend amount are same";
                $_SESSION['discount_name'] = $discountName;
                $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
                $_SESSION['digit'] = $_POST['digit'];
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['status'] = $_POST['status'];
            }
            if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmounts) || in_array($_POST['digit'][$i], $discountDigits)) {
                header('location:../discount/add.php');
                exit;
            }
            $discountDigits[$i] = $_POST['digit'][$i];
            $minimumSpendAmounts[$i] = $_POST['minimum_spend_amount'][$i];
        }

        for ($i = 0; $i < count($_POST['digit']); $i++) {
            if (empty($_POST['minimum_spend_amount'][$i]) || empty($_POST['digit'][$i])) {
                header('location:../discount/add.php');
                exit;
            }
            if ($_POST['type'] == "1" && $_POST['digit'][$i] > 100) {
                header('location:../discount/add.php');
                exit;
            }
            if (((int) $discounts[0]['discount_name']) > 0) {
                header('location:../discount/add.php');
                exit;
            }
        }

        if (empty($_POST['name']) || empty($_POST['type']) || empty($_POST['status'])) {
            header('location:../discount/add.php');
            exit;
        }

        $discountType = $_POST['type'];
        $discountStatus = $_POST['status'];

        $insertDiscount = $pdo->prepare("INSERT INTO `discount`(`name`,`type`,`status`) VALUES(:name, :type, :status)");
        $insertDiscount->bindParam(':name', $discountName);
        $insertDiscount->bindParam(':type', $discountType);
        $insertDiscount->bindParam(':status', $discountStatus);
        $insertDiscount->execute();
        for ($i = 0; $i < count($discountDigits); $i++) {
            $insertTierDiscount = $pdo->prepare("INSERT INTO discount_tier(`discount_id`,`minimum_spend_amount`,`discount_digit`) SELECT max(`id`),$minimumSpendAmounts[$i],$discountDigits[$i] FROM `discount`");
            $isExecute = $insertTierDiscount->execute();
        }
        if ($isExecute) {
            $_SESSION['message'] = "Discount added successfully.";
            header('location:../discount/list.php');
            exit;
        }
        $_SESSION['message'] = "Something went wrong";
        $_SESSION['discount_name'] = $discountName;
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
                                <label for="discount-name">Discount Name</label>
                                <input type="text" class="form-control" id="discount-name"
                                    placeholder="Discount Name" name="name"
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
                                <label for="discountType">Type Of Discount</label>
                                <select id="discountType" class="form-control" name="type" >
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
                            <h6><u><b>Minimum Spends</b></u></h6>
                            <div class="form-group">
                                <div>
                                    <button type="button"
                                        class="input-group-text bg-primary text-white"
                                        style="margin-left: 350px;"
                                        onclick="addMinimumSpendRow()"
                                    >
                                        Add new
                                    </button>
                                </div>
                                <div class="minimum-spend-row-container">
                                    <!-- Here added Minimum Spends Row using javascript -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="discountStatus">Status</label>
                                <select id="discountStatus"
                                    class="form-control"
                                    name="status"
                                    >
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
<?php if (isset($_SESSION['digit'])) { ?>
    <script> sessionRenderMinimumSpendTemplate(); </script>
        <?php for ($i = 1; $i < count($_SESSION['digit']); $i++) { ?>
                <script>sessionAddMinimumSpendRow();</script>
        <?php }
        unset($_SESSION['digit']);
} else { ?>
        <script>renderMinimumSpendTemplate()</script>
<?php }
    include '../layout/footer.php';
?>