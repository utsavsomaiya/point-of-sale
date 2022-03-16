<?php
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=point-of-sale', 'root', '1234');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $exception) {
        die($exception->getMessage());
    }
