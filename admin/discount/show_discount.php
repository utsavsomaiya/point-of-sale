<?php
    session_start();
    require '../layout/db_connect.php';
    $fetchDiscount = $pdo->prepare('SELECT * FROM `discount` ORDER BY `id` DESC');
    $fetchDiscount->execute();
    $discounts = $fetchDiscount->fetchAll();
?>
<?php  include '../layout/header.php';?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card-body">
                    <h4 class="card-title">
                        <span style="margin-right:80px;">Discounts</span>
                        <a href="add_discount.php">Add New Discount</a>
                    </h4>
                    <?php if (sizeof($discounts) > 0) { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th colspan='2'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($discounts as $discount) { ?>
                                    <tr>
                                        <td><?= $discount['id'] ?></td>
                                        <td><?= $discount['name'] ?></td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox"
                                                value="<?= $discount['status'] ?>"
                                                <?php
                                                    if ($discount['status'] == "2") {
                                                        echo "checked";
                                                    }
                                                ?>
                                                onclick=
                                                "discountStatusChanged(<?= $discount['id'] ?>,<?= $discount['status']?>)"
                                                >
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <a
                                            href="../discount/edit_discount.php?id=<?= $discount['id'] ?>">
                                                <img src="/admin/image/edit-icon.png">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:deleteDiscount(<?= $discount['id'] ?>)">
                                                <i class="fa fa-trash-o" style="font-size:24px"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php
                        } else {
                            echo "No discounts found.";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/admin/js/discount.js"></script>
<?php include '../layout/footer.php'; ?>