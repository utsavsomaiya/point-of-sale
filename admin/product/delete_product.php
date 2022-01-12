<?php
session_start();
require '../layout/db_connect.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $fetch = $pdo->prepare("delete from product where id=:id");
    $fetch->bindParam(':id', $id);
    $fetch->execute();
    $_SESSION['msg'] = "Record deleted";
    header('location:../product/show_product.php');
}
