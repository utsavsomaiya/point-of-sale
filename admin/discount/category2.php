<?php
    if (empty($_POST['name'])) {
        $_SESSION['name_alert'] = "Please enter discount name.";
        $_SESSION['category'] = $_POST['category'];
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['product']= $_POST['products'];
        $_SESSION['type'] = $_POST['type'];
        $_SESSION['status'] = $_POST['status'];
    }
