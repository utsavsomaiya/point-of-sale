<?php
    if (empty($_POST['name'])) {
        $_SESSION['name_alert'] = "Please enter discount name.";
        $_SESSION['category'] = $_POST['category'];
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['digit']= $_POST['digit'];
        $_SESSION['type'] = $_POST['type'];
        $_SESSION['status'] = $_POST['status'];
    }
    if ($_POST['category'] == "") {
        $_SESSION['category_alert'] = "Please select discount category.";
        $_SESSION['discount_name'] = $_POST['name'];
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['digit']= $_POST['digit'];
        $_SESSION['type'] = $_POST['type'];
        $_SESSION['status'] = $_POST['status'];
    }

    if (empty($_POST['type'])) {
        $_SESSION['type_alert'] = "Please select discount type.";
        $_SESSION['discount_name'] = $_POST['name'];
        $_SESSION['category'] = $_POST['category'];
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['digit']= $_POST['digit'];
        $_SESSION['status'] = $_POST['status'];
    }

    if (empty($_POST['status'])) {
        $_SESSION['status_alert'] = "Please select discount status.";
        $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
        $_SESSION['category'] = $_POST['category'];
        $_SESSION['digit']= $_POST['digit'];
        $_SESSION['type'] = $_POST['type'];
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
        $_SESSION['digit']= $_POST['digit'];
        $_SESSION['type'] = $_POST['type'];
        $_SESSION['status'] = $_POST['status'];
        header('location:../discount/add_discount.php');
        exit;
    }

    for ($i = 0; $i < count($_POST['digit']); $i++) {
        if (empty($_POST['digit'][$i])) {
            $_SESSION['digit_alert'][$i] = "Please enter digit.";
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['discount_name'] = $_POST['name'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit']= $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (empty($_POST['minimum_spend_amount'][$i])) {
            $_SESSION['minimum_spend_amount_alert'][$i] = "Please enter minimum spend amount.";
            $_SESSION['discount_name'] = $_POST['name'];
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit']= $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['status'] = $_POST['status'];
        }

        if ($_POST['type'] == "1" && $_POST['digit'][$i] > 100) {
            $_SESSION['digit_alert'][$i] = "Percentage is not greater than 100.";
            $_SESSION['discount_name'] = $discountName;
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit'] = $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['status'] = $_POST['status'];
        }
    }


    $discountDigits = [];
    $minimumSpendAmounts = [];
    for ($i = 0; $i < count($_POST['digit']); $i++) {
        if (in_array($_POST['digit'][$i], $discountDigits)) {
            $_SESSION['digit_alert'][$i] = "Discount digits are same";
            $_SESSION['discount_name'] = $discountName;
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['minimum_spend_amount']= $_POST['minimum_spend_amount'];
            $_SESSION['digit'] = $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmounts)) {
            $_SESSION['minimum_spend_amount_alert'][$i] = "Minimum spend amount are same";
            $_SESSION['discount_name'] = $discountName;
            $_SESSION['category'] = $_POST['category'];
            $_SESSION['minimum_spend_amount'] = $_POST['minimum_spend_amount'];
            $_SESSION['digit'] = $_POST['digit'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['status'] = $_POST['status'];
        }
        if (in_array($_POST['minimum_spend_amount'][$i], $minimumSpendAmounts) || in_array($_POST['digit'][$i], $discountDigits)) {
            header('location:../discount/add_discount.php');
            exit;
        }
        $discountDigits[$i] = $_POST['digit'][$i];
        $minimumSpendAmounts[$i] = $_POST['minimum_spend_amount'][$i];
    }

    for ($i = 0; $i < count($_POST['digit']); $i++) {
        if (empty($_POST['minimum_spend_amount'][$i]) || empty($_POST['digit'][$i])) {
            header('location:../discount/add_discount.php');
            exit;
        }
        if ($_POST['type'] == "1" && $_POST['digit'][$i] > 100) {
            header('location:../discount/add_discount.php');
            exit;
        }
    }

    if (empty($_POST['name']) || empty($_POST['type']) || empty($_POST['status']) || empty($_POST['category'])) {
        header('location:../discount/add_discount.php');
        exit;
    }


    $insertDiscount = $pdo->prepare("INSERT INTO `discount`(`name`,`type`,`status`,`category`) VALUES(:name, :type, :status, :category)");
    $insertDiscount->bindParam(':name', $_POST['name']);
    $insertDiscount->bindParam(':type', $_POST['type']);
    $insertDiscount->bindParam(':status', $_POST['status']);
    $insertDiscount->bindParam(':category', $_POST['category']);
    $insertDiscount->execute();

    for ($i = 0; $i < count($discountDigits); $i++) {
        $insertTierDiscount = $pdo->prepare("INSERT INTO discount_tier(`discount_id`,`minimum_spend_amount`,`discount_digit`,`discount_product`) SELECT max(`id`),$minimumSpendAmounts[$i],$discountDigits[$i],NULL FROM `discount`");
        $isExecute = $insertTierDiscount->execute();
    }
    if ($isExecute) {
        $_SESSION['message'] = "Discount added successfully.";
        header('location:../discount/show_discount.php');
        exit;
    }
    $_SESSION['message'] = "Something went wrong";
    $_SESSION['discount_name'] = $discountName;
    $_SESSION['digit'] = $discountDigits;
    $_SESSION['minimum_spend_amount'] = $minimumSpendAmounts;
    $_SESSION['type'] = $_POST['type'];
    $_SESSION['status'] = $_POST['status'];
    header('location:../discount/add_discount.php');
    exit;
