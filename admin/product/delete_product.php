<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $productId = $_GET['id'];
        $deleteProduct = $pdo->prepare("DELETE FROM `product` WHERE `id` = :id ");
        $deleteProduct->bindParam(':id', $productId);
        $isExecuted = $deleteProduct->execute();
        if ($isExecuted) {
            $_SESSION['message']="Product deleted successfully.";
            header('location:../product/show_product.php');
            exit;
        }
        $_SESSION['message']="Something went wrong.";
        header('location:../product/show_product.php');
        exit;
    }
