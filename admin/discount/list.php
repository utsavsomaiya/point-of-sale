<?php
session_start();
require '../layout/db_connect.php';
?>
<?php  include '../layout/header.php';?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <span style="margin-right:80px;">Discounts</span>
                        <a href="add.php">Add New Discount</a>
                    </h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Digit</th>
                                <th>Status</th>
                                <th colspan='2'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                require '../layout/db_connect.php';
                                $fetch = $pdo->prepare('SELECT * FROM `discount` ORDER BY `id` DESC');
                                $fetch->execute();
                                $result = $fetch->fetchAll();
                                foreach ($result as $discount) {
                                    if (!empty($discount)) {
                                        ?>
                            <tr>
                                <td><?= $discount['id'] ?></td>
                                <?php
                                        if ($discount['type'] == "1") {
                                            ?>
                                <td><?= $discount['digit']."%" ?></td>
                                <?php
                                        } else { ?>
                                <td><?= "$".$discount['digit'] ?></td>
                                <?php } ?>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" value="<?=$discount['status']; ?>" <?php
                                                    if ($discount['status'] == "2") {
                                                        echo "checked";
                                                    } ?> onclick="stateChanged(<?= $discount['id'] ; ?>,<?= $discount['status']?>)">
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td><a href="../discount/edit.php?id=<?= $discount['id'] ?>"><img
                                            src="/admin/image/edit-icon.png" /></a></td>
                                <td><a href="javascript:alert_d(<?= $discount['id'] ?>)"><i class="fa fa-trash-o"
                                            style="font-size:24px"></i></a>
                                </td>
                            </tr>
                            <?php
                                    } else {
                                        echo "No Record Found..";
                                    }
                                }
                                ?>
                        </tbody>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="custom.js"></script>
    <?php
    include '../layout/footer.php';
    unset($_SESSION['msg']);
?>