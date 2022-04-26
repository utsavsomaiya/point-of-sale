<?php
    if (empty($_POST['name'])) {
        $_SESSION['name_alert'] = "Please enter discount name.";
        $_SESSION['category'] = $_POST['category'];
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['product']= $_POST['products'];
        $_SESSION['status'] = $_POST['status'];
    }

    if ($_POST['category'] == "") {
        $_SESSION['category_alert'] = "Please select discount category.";
        $_SESSION['discount_name'] = $_POST['name'];
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['product']= $_POST['products'];
        $_SESSION['status'] = $_POST['status'];
    }

    if (empty($_POST['status'])) {
        $_SESSION['status_alert'] = "Please select discount status.";
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['category'] = $_POST['category'];
        $_SESSION['product']= $_POST['products'];
        $_SESSION['discount_name'] = $_POST['name'];
    }

    $discountName = $_POST['name'];
    $fetchDiscount = $pdo->prepare('SELECT COUNT(*) AS discount_name FROM `discount` WHERE `name` = :discount_name ');
    $fetchDiscount->bindParam(':discount_name', $discountName);
    $fetchDiscount->execute();
    $discounts = $fetchDiscount->fetchAll();

    if (((int) $discounts[0]['discount_name']) > 0) {
        $_SESSION['name_alert'] = "Already taken this name";
        $_SESSION['discount_name'] = $discountName;
        $_SESSION['category'] = $_POST['category'];
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['product']= $_POST['products'];
        $_SESSION['status'] = $_POST['status'];
        header('location:../discount/add_discount.php');
        exit;
    }

    for ($i = 0; $i < count($_POST['products']); $i++) {
        if (empty($_POST['products'][$i])) {
            $_SESSION['product_alert'][$i] = "Please select product.";
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['discount_name'] = $_POST['name'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (empty($_POST['minimum_spend_amount'][$i])) {
            $_SESSION['minimum_spend_amount_alert'][$i] = "Please enter minimum spend amount.";
            $_SESSION['discount_name'] = $_POST['name'];
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['product']= $_POST['products'];
            $_SESSION['status'] = $_POST['status'];
        }
    }


    $discountProducts = [];
    $minimumSpendAmounts = [];

    for ($i = 0; $i < count($_POST['products']); $i++) {
        if (in_array($_POST['products'][$i], $discountProducts)) {
            $_SESSION['product_alert'][$i] = "Products are same";
            $_SESSION['discount_name'] = $discountName;
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['minimum_spend_amount']= $_POST['minimum_spend_amount'];
            $_SESSION['product'] = $_POST['products'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmounts)) {
            $_SESSION['minimum_spend_amount_alert'][$i] = "Minimum spend amount are same";
            $_SESSION['discount_name'] = $discountName;
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['product'] = $_POST['products'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmounts) || in_array($_POST['products'][$i], $discountProducts)) {
            header('location:../discount/add_discount.php');
            exit;
        }
        $discountProducts[$i] = $_POST['products'][$i];
        $minimumSpendAmounts[$i] = $_POST['minimum_spend_amount'][$i];
    }

    for ($i = 0; $i < count($_POST['products']); $i++) {
        if (empty($_POST['minimum_spend_amount'][$i]) || empty($_POST['products'][$i])) {
            header('location:../discount/add_discount.php');
            exit;
        }
    }

    if (empty($_POST['name']) || empty($_POST['status']) || $_POST['category'] == "") {
        header('location:../discount/add_discount.php');
        exit;
    }

    $insertDiscount = $pdo->prepare("INSERT INTO `discount`(`name`,`type`,`status`,`category`) VALUES(:name, NULL, :status, :category)");
    $insertDiscount->bindParam(':name', $_POST['name']);
    $insertDiscount->bindParam(':status', $_POST['status']);
    $insertDiscount->bindParam(':category', $_POST['category']);
    $insertDiscount->execute();

    for ($i = 0; $i < count($discountProducts); $i++) {
        $insertTierDiscount = $pdo->prepare("INSERT INTO discount_tier(`discount_id`,`minimum_spend_amount`,`discount_digit`,`discount_product`) SELECT max(`id`),$minimumSpendAmounts[$i],NULL,'$discountProducts[$i]' FROM `discount`");
        $isExecute = $insertTierDiscount->execute();
    }
    if ($isExecute) {
        $_SESSION['message'] = "Discount added successfully.";
        header('location:../discount/show_discount.php');
        exit;
    }
    $_SESSION['message'] = "Something went wrong";
    $_SESSION['discount_name'] = $discountName;
    $_SESSION['product'] = $discountProducts;
    $_SESSION['minimum_spend_amount'] = $minimumSpendAmounts;
    $_SESSION['status'] = $_POST['status'];
    header('location:../discount/add_discount.php');
    exit;
