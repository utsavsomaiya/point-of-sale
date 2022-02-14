<?php
     if (!empty($_SESSION['name'])) {
     } else {
         header('location:/admin/auth/login.php');
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Star Admin2</title>
	<link rel="stylesheet" href="/admin/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="/admin/vendors/mdi/css/custom_styles.css">
	<link rel="stylesheet" href="/admin/vendors/simple-line-icons/css/simple-line-icons.css">
	<link rel="stylesheet" href="/admin/css/vertical-layout-light/style.css">
	<link rel="stylesheet" href="/admin/vendors/ti-icons/css/themify-icons.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>
	.hvr-underline-from-center {
		display: inline-block;
		vertical-align: middle;
		-webkit-transform: perspective(1px) translateZ(0);
		transform: perspective(1px) translateZ(0);
		box-shadow: 0 0 1px rgba(0, 0, 0, 0);
		position: relative;
		overflow: hidden;
	}

	.hvr-underline-from-center:before {
		content: "";
		position: absolute;
		z-index: -1;
		left: 51%;
		right: 51%;
		bottom: 0;
		background: #2098D1;
		height: 4px;
		-webkit-transition-property: left, right;
		transition-property: left, right;
		-webkit-transition-duration: 0.3s;
		transition-duration: 0.3s;
		-webkit-transition-timing-function: ease-out;
		transition-timing-function: ease-out;
	}

	.hvr-underline-from-center:hover:before,
	.hvr-underline-from-center:focus:before,
	.hvr-underline-from-center:active:before {
		left: 0;
		right: 0;
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
				<div class="me-3">
					<button class="navbar-toggler navbar-toggler align-self-center" type="button"
						data-bs-toggle="minimize">
						<span class="icon-menu"></span>
					</button>
				</div>
				<div>
					<a class="navbar-brand brand-logo">
						<img src="/admin/images/logo.svg" alt="logo" />
					</a>
					<a class="navbar-brand brand-logo-mini">
						<img src="/admin/images/logo-mini.svg" alt="logo" />
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
							<img class="img-xs rounded-circle" src="/admin/images/faces/face8.jpg" alt="Profile image"> </a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
							<div class="dropdown-header text-center">
								<img class="img-md rounded-circle" src="/admin/images/faces/face8.jpg" alt="Profile image">
								<p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['name']; ?></p>
							</div>
							<a class="dropdown-item" href="/admin/auth/signout.php"><i
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
			<nav class="sidebar sidebar-offcanvas">
				<ul class="nav">
					<li class="nav-item">
						<a class="nav-link" href="/admin/dashboard.php">
							<i class="mdi mdi-grid-large menu-icon"></i>
							<span class="menu-title">Dashboard</span>
						</a>
					</li>
					<li class="nav-item nav-category">Products</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/product/show_product.php" >
							<i class="menu-icon mdi mdi-floor-plan"></i>
							<span class="menu-title">Products</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/category/show_category.php" >
							<i class="menu-icon mdi mdi-floor-plan"></i>
							<span class="menu-title">Categories</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/settings.php" >
							<i class="menu-icon mdi mdi-floor-plan"></i>
							<span class="menu-title">Discount</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/sales.php" >
							<i class="menu-icon mdi mdi-floor-plan"></i>
							<span class="menu-title">Sales</span>
						</a>
					</li>
				</ul>
			</nav>