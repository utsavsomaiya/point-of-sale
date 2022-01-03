<?php
    session_start();
    require '../layout/db_connect.php';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $res = $pdo->prepare("select id from category where name in(select category from product)");
        $res->execute();
        $res = $res->fetchAll();
        foreach ($res as $r) {
            if ($r['id'] == $id) {
                $_SESSION['msg'] = "Don't delete this Category";
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
    }
