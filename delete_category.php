<?php
session_start();
require '../layout/db_connect.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $res = $pdo->prepare("select p1.category_id from product p1,category c1 where p1.category_id = c1.id");
    $res->execute();
    $res = $res->fetchAll();
    foreach ($res as $r) {
        if ($r['category_id'] == $id) {
            $found = 1;
            break;
        }
    }
    if ($found == 1) {
        $_SESSION['msg'] = "Don't delete this Category";
        header('location:/admin/category/show_category.php');
    } else {
        $fetch = $pdo->prepare("delete from category where id= :id");
        $fetch->bindParam(':id', $id);
        $res = $fetch->execute();
        if (isset($res)) {
            $_SESSION['msg'] = "Record deleted";
            header("location:/admin/category/show_category.php");
        }
    }
}
