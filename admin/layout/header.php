<?php
    if (empty($_SESSION['login'])) {
        header('location:/admin/auth/login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Retail Shop(Admin)</title>
        <link rel="stylesheet" href="/admin/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="/admin/vendors/mdi/css/custom_styles.css">
        <link rel="stylesheet" href="/admin/vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="/admin/css/vertical-layout-light/style.css">
        <link rel="stylesheet" href="/admin/vendors/ti-icons/css/themify-icons.css">
        <link rel="stylesheet" href="/admin/css/custom.css">
        <link rel="icon" href="/admin/image/retail-store-icon-18.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <style>
        .thumbnail:hover {
        border: 3px dashed pink;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <body>
        <div class="container-scroller">
            <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                    <div>
                            <a href="#">
                                <span style="font-size:large; color:black;">
                                    Retail
                                    <b style="color:blue">
                                        Store
                                    </b>
                                </span>
                            </a>
                    </div>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-top">
                    <ul class="navbar-nav">
                        <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                            <h1 class="welcome-text">
                                Good Morning,
                                <span class="text-black fw-bold">
                                    <?php
                                        if (!empty($_SESSION['name'])) {
                                            echo $_SESSION['name'];
                                        }
                                    ?>
                                </span>
                            </h1>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                            <a class="nav-link" id="UserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="img-xs rounded-circle" src="/admin/image/face8.jpg" alt="Profile image">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                                <div class="dropdown-header text-center">
                                    <img class="img-md rounded-circle" src="/admin/image/face8.jpg" alt="Profile image">
                                    <p class="mb-1 mt-3 font-weight-semibold">
                                        <?= $_SESSION['name'] ?>
                                    </p>
                                </div>
                                <a class="dropdown-item" href="/admin/auth/signout.php">
                                    <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>
                                    Sign Out
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid page-body-wrapper">
                <nav class="sidebar sidebar-offcanvas" >
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard.php">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item nav-category">Products</li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/product/show_product.php">
                                <i class="menu-icon mdi mdi-floor-plan"></i>
                                <span class="menu-title">Products</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/category/show_category.php">
                                <img src="/images/categories.png" style="width: 20px; margin-right: 10px;">
                                <span class="menu-title">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item nav-category">Discounts</li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/discount/show_discount.php">
                                <img src="/images/discount.png" style="width: 20px; margin-right: 10px;">
                                <span class="menu-title">Discount</span>
                            </a>
                        </li>
                        <li class="nav-item nav-category">Sales Details</li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/sales.php">
                                <img src="/images/sales.png" style="width: 20px; margin-right: 10px;">
                                <span class="menu-title">Sales</span>
                            </a>
                        </li>
                    </ul>
                </nav>
