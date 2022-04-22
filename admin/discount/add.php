<?php
    session_start();
    require '../layout/db_connect.php';
    $fetchProducts = $pdo->prepare('SELECT * FROM `product`');
    $fetchProducts->execute();
    $products = $fetchProducts->fetchAll();
    if (isset($_POST['submit'])) {
        if (!isset($_POST['digit'])) {
            require '../discount/category2.php';
        }
        if (!isset($_POST['products'])) {
            require '../discount/category1.php';
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
                                <label for="discount-name">Discount Name</label>
                                <input type="text" class="form-control" id="discount-name"
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
                                <label for="discountCategory">Discount Category</label>
                                <select id="discountCategory" class="form-control category" name="category">
                                        <option>--Select Category--</option>
                                        <option value="1">Price Discount</option>
                                        <option value="2">Gift Discount</option>
                                </select>
                                <label class="text-danger">
                                </label>
                            </div>
                            <div class="categorized-container">
                                <?php  require '../discount/tier_template1.php'; ?>
                                <?php require '../discount/tier_template2.php'; ?>
                            </div>
                            <div class="form-group">
                                <label for="discountStatus">Status</label>
                                <select id="discountStatus"
                                    class="form-control"
                                    name="status"
                                    required
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
<?php include '../discount/tier_template1.php'; ?>
<?php include '../discount/tier_template2.php'; ?>
<script>
    /* var errorDiscountDigit = [];
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
    <?php } ?> */
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