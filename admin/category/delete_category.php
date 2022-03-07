<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $categoryId = $_GET['id'];
        $fetchProduct = $pdo->prepare("SELECT * FROM `product` where `category_id` = :id");
        $fetchProduct->bindParam(':id', $categoryId);
        $fetchProduct->execute();
        $count = $fetchProduct->rowCount();
        if ($count > 0) {
            $_SESSION['msg'] = "Cannot delete this category as it is used by one or more products.";
            header('location:/admin/category/show_category.php');
            exit;
        }
        $deleteCategory = $pdo->prepare("DELETE FROM `category` where `id` = :id");
        $deleteCategory->bindParam(':id', $categoryId);
        $isExecuted = $deleteCategory->execute();
        if ($isExecuted) {
            $_SESSION['msg']="Record deleted";
            header('location:/admin/category/show_category.php');
            exit;
        }
        $_SESSION['msg']="Something went wrong";
        header('location:/admin/category/show_category.php');
        exit;
    }
