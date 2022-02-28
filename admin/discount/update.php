<?php
require '../layout/db_connect.php';
$id = $_POST['id'];
$data = $_POST['state'];
$fetch = $pdo->prepare("UPDATE `discount` SET `status`=:state WHERE `id` = :id ");
$fetch->bindParam(':state', $data);
$fetch->bindParam(':id', $id);
$result = $fetch->execute();
