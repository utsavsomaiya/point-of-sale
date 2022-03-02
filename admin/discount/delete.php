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
            $_SESSION['msg'] = "Cannot delete this discount";
            header('location:/admin/discount/list.php');
            exit;
        } else {
            $deleteDiscount = $pdo->prepare("DELETE FROM `discount` WHERE `id`= :id");
            $deleteDiscount->bindParam(':id', $discountId);
            $isExecute = $deleteDiscount->execute();
            if ($isExecute) {
                $_SESSION['msg']="Record deleted";
                header('location:../discount/list.php');
                exit;
            }
        }
    }
