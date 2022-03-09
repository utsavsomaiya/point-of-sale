<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $categoryId = $_GET['id'];
        $fetchCategory = $pdo->prepare("SELECT `name` FROM `category` WHERE `id` = :id");
        $fetchCategory->bindParam(':id', $categoryId);
        $fetchCategory->execute();
        $categories = $fetchCategory->fetchAll();
        foreach ($categories as $category) {
            $categoryName = $category['name'];
        }
    }
    if (isset($_POST['submit'])) {
        if (empty($_POST['category_name'])) {
            $_SESSION['name_alert'] = "Please enter category name.";
            header("location:/admin/category/edit_category.php?id=$categoryId");
            exit;
        }
        $categoryName = $_POST['category_name'];
        $updateCategory = $pdo->prepare("UPDATE `category` set `name` = :name WHERE `id` = :id");
        $updateCategory->bindParam(':name', $categoryName);
        $updateCategory->bindParam(':id', $categoryId);
        $isExecuted = $updateCategory->execute();
        if ($isExecuted) {
            $_SESSION['message'] = "Category updated successfully.";
            header('location:../category/show_category.php');
            exit;
        }
        $_SESSION['message'] = "Something went wrong.";
        header("location:../category/edit_category.php?id=$categoryId");
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
                        <h4 class="card-title">Edit category</h4>
                        <form class="forms-sample" method="post">
                            <div class="form-group">
                                <label for="category-name">Category Name</label>
                                <input type="text" class="form-control" id="category-name"
                                    placeholder="Category Name" name="category_name" required
                                    <?php
                                        if (isset($categoryName)) {
                                            echo "value=\"".$categoryName."\"";
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
                            <button type="submit" class="btn btn-primary me-2" name="submit" onclick="toast()">
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