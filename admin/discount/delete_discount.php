<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $discountId = $_GET['id'];
        $fetchSales = $pdo->prepare("SELECT * FROM `sales` WHERE `discount_id` = :id LIMIT 1");
        $fetchSales->bindParam(':id', $discountId);
        $fetchSales->execute();
        $count = $fetchSales->rowCount();
        if ($count == 1) {
            $_SESSION['message'] = "Cannot delete this discount.";
            header('location:/admin/discount/show_discount.php');
            exit;
        }

        $deleteDiscount = $pdo->prepare("DELETE FROM `discount` WHERE `id` = :id ");
        $deleteDiscount->bindParam(':id', $discountId);
        $deleteDiscount->execute();
        $deleteDiscountTier = $pdo->prepare("DELETE FROM `discount_tier` WHERE `discount_id`= :id");
        $deleteDiscountTier->bindParam(':id', $discountId);
        $isExecute = $deleteDiscountTier->execute();
        if ($isExecute) {
            $_SESSION['message'] = "Discount deleted successfully.";
            header('location:../discount/show_discount.php');
            exit;
        }
        $_SESSION['message'] = "Something went wrong.";
        header("location:../discount/delete_discount.php?id=$discountId");
        exit;
    }
