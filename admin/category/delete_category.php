<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $res = $pdo->prepare("SELECT `id` FROM `category` where `id` in(SELECT `category_id` FROM `product`)");
        $res->execute();
        $res = $res->fetchAll();
        foreach ($res as $r) {
            if ($r['id'] == $id) {
                $found = 1;
                break;
            }
        }
        if ($found == 1) {
            $_SESSION['msg'] = "Cannot delete this category as it is used by one ore more products.";
            header('location:/admin/category/show_category.php');
        } else {
            $fetch = $pdo->prepare("delete from category where id='$id'");
            $res = $fetch->execute();
            if (isset($res)) {
                $_SESSION['msg']="Record deleted";
                header('location:/admin/category/show_category.php');
            }
        }
    }
