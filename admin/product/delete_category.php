<?php
session_start();
    try {
        $pdo =  new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $fetch = $pdo->prepare("delete from category where id='$id'");
        $res = $fetch->execute();
        if (isset($res)) {
            $_SESSION['msg']="Record deleted";
            header('location:../product/show_category.php');
        }
    }
