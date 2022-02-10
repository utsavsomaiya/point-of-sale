<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=point-of-sale', 'root', '1234');
} catch (PDOException $e) {
    die($e->getMessage());
}
