<?php
session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $fetch = $pdo->prepare("SELECT `status` FROM `discount` WHERE `status`= 2 AND`id`= :id LIMIT 1");
        $fetch->bindParam(':id', $id);
        $fetch->execute();
        $count = $fetch->rowCount();
        if ($count == 1) {
            $_SESSION['msg'] = "Cannot delete this discount because already activate..";
            header('location:/admin/discount/list.php');
        } else {
            $fetch = $pdo->prepare("DELETE FROM `discount` WHERE `id`= :id");
            $fetch->bindParam(':id', $id);
            $fetch->execute();
            $_SESSION['msg']="Record deleted";
            header('location:../discount/list.php');
        }
    }
