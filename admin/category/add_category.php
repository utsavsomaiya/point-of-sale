<?php
    session_start();
    if (isset($_POST['submit'])) {
        if (empty($_POST['category_name'])) {
            $_SESSION['name_alert'] = "Please enter category name.";
            header('location:/admin/category/add_category.php');
            exit;
        }
        require '../layout/db_connect.php';
        $categoryName = $_POST['category_name'];
        $_SESSION['category_name'] = $categoryName;
        $insertCategory = $pdo->prepare("INSERT INTO `category`(`name`) VALUES(:categoryName)");
        $insertCategory->bindParam('categoryName', $categoryName);
        $isExecuted = $insertCategory->execute();
        if ($isExecuted) {
            $_SESSION['message'] = "Category added successfully.";
            header('location:/admin/category/show_category.php');
            exit;
        }
        $_SESSION['message'] = "Something went wrong.";
        header('location:/admin/category/add_category.php');
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
                        <h4 class="card-title">Add new Category</h4>
                        <form class="forms-sample" method="post">
                            <div class="form-group">
                                <label for="category-name">Category Name</label>
                                <input type="text" class="form-control" id="category-name"
                                    placeholder="Category Name" name="category_name"
                                    <?php
                                        if (isset($_SESSION['category_name'])) {
                                            echo "value=\"".$_SESSION['category_name']."\"";
                                            unset($_SESSION['category_name']);
                                        }
                                    ?>
                                required>
                                <label class="text-danger">
                                    <?php
                                        if (isset($_SESSION['name_alert'])) {
                                            echo $_SESSION['name_alert'];
                                            unset($_SESSION['name_alert']);
                                        }
                                    ?>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit">
                                Submit
                            </button>
                            <a href="/admin/category/show_category.php" class="btn btn-light" name="cancel">
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