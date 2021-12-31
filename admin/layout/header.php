<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Star Admin2 </title>
	<link rel="stylesheet" href="/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="/vendors/mdi/css/custom_styles.css">
	<link rel="stylesheet" href="/vendors/simple-line-icons/css/simple-line-icons.css">
	<link rel="stylesheet" href="/css/vertical-layout-light/style.css">
</head>

<body>
	<div class="container-scroller">
		<!-- partial:partials/_navbar.html -->
		<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
			<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
				<div class="me-3">
					<button class="navbar-toggler navbar-toggler align-self-center" type="button"
						data-bs-toggle="minimize">
						<span class="icon-menu"></span>
					</button>
				</div>
				<div>
					<a class="navbar-brand brand-logo" href="index.html">
						<img src="/images/logo.svg" alt="logo" />
					</a>
					<a class="navbar-brand brand-logo-mini" href="index.html">
						<img src="/images/logo-mini.svg" alt="logo" />
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
                                    } else {
                                        header('location:../auth/index.php');
                                    }
                                ?>
							</span>
						</h1>
					</li>
				</ul>
				<ul class="navbar-nav ms-auto">
					<li class="nav-item dropdown d-none d-lg-block user-dropdown">
						<a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
							<img class="img-xs rounded-circle" src="/images/faces/face8.jpg" alt="Profile image"> </a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
							<div class="dropdown-header text-center">
								<img class="img-md rounded-circle" src="/images/faces/face8.jpg" alt="Profile image">
								<p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['name']; ?></p>
								<p class="fw-light text-muted mb-0"><?php echo $_SESSION['name'].'@gmail.com'?></p>
							</div>
							<a class="dropdown-item" href="../auth/signout.php"><i
									class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
						</div>
					</li>
				</ul>
				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
					data-bs-toggle="offcanvas">
					<span class="mdi mdi-menu"></span>
				</button>
			</div>
		</nav>
		<div class="container-fluid page-body-wrapper">
			<nav class="sidebar sidebar-offcanvas" id="sidebar">
				<ul class="nav">
					<li class="nav-item">
						<a class="nav-link" href="../layout/dashboard.php">
							<i class="mdi mdi-grid-large menu-icon"></i>
							<span class="menu-title">Dashboard</span>
						</a>
					</li>
					<li class="nav-item nav-category">Products</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
							aria-controls="ui-basic">
							<i class="menu-icon mdi mdi-floor-plan"></i>
							<span class="menu-title">Products</span>
							<i class="menu-arrow"></i>
						</a>
						<div class="collapse" id="ui-basic">
							<ul class="nav flex-column sub-menu">
								<li class="nav-item"> <a class="nav-link" href="../product/show_product.php">Show Products</a></li>
								<li class="nav-item"> <a class="nav-link" href="../product/add_product.php">Add Products</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</nav>