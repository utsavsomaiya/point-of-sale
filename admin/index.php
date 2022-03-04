<?php
    session_start();
    if (isset($_SESSION['login'])) {
        header('location:/admin/dashboard.php');
        exit;
    } else {
        header('location:/admin/auth/login.php');
        exit;
    }
