<?php
    session_start();
    if (isset($_GET['s1'])) {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        $name = $_GET['email'];
        $password = $_GET['password'];
        $abc = $pdo->prepare("select id from admin where name='$name' and password='$password' and 1=1");
        $abc->execute();

        $result = $abc->fetchAll();
        if (!$result) {
            echo '<a href="index.php">Error: cannot execute query</a>';
            exit;
        } else {
            $_SESSION['name'] = $_GET['email'];
            $_SESSION["login"] = "OK";
            header('location:../layout/dashboard.php');
        }
    }
