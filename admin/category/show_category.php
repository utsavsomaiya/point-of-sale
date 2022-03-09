<?php
    session_start();
    require '../layout/db_connect.php';
    $fetchCategory = $pdo->prepare('SELECT * FROM `category`');
    $fetchCategory->execute();
    $categories = $fetchCategory->fetchAll();
?>
<?php include '../layout/header.php'; ?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card-body">
                    <h4 class="card-title">
                        <span style="margin-right:80px;">Products Category</span>
                        <a href="add_category.php">Add New Category</a>
                    </h4>
                    <?php if (sizeof($categories) > 0) { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th colspan='2'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category) { ?>
                                    <tr>
                                        <td><?= $category['id']?></td>
                                        <td><?= $category['name'] ?></td>
                                        <td>
                                            <a href="../category/edit_category.php?id=<?= $category['id']?>">
                                                <img src="/admin/image/edit-icon.png">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:deleteCategory(<?= $category['id']?>)">
                                                <i class="fa fa-trash-o" style="font-size:24px"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php
                        } else {
                            echo "No categories found.";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layout/footer.php'; ?>