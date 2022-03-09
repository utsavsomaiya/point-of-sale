<?php
    session_start();
    if (isset($_POST['submit'])) {
        if (empty($_POST['email'])) {
            $_SESSION['name_alert'] = "Please enter username.";
        }
        if (empty($_POST['password'])) {
            $_SESSION['password_alert'] = "Please enter password.";
        }
        if (empty($_POST['password']) || empty($_POST['email'])) {
            header('location:/admin/auth/login.php');
            exit;
        }
        $username = $_POST['email'];
        $_SESSION['name'] = $username;
        $password = $_POST['password'];
        require '../layout/db_connect.php';
        $fetchAdmin = $pdo->prepare("SELECT * FROM `admin` WHERE `name` = :name AND `password` = :password");
        $fetchAdmin->bindParam(':name', $username);
        $fetchAdmin->bindParam(':password', $password);
        $fetchAdmin->execute();
        $count = $fetchAdmin->rowCount();
        if ($count > 0) {
            $_SESSION['message'] = "Login Successfully";
            $_SESSION['login'] = "User logged in..";
            header('location:../dashboard.php');
            exit;
        } else {
            $_SESSION['alert'] = "Incorrect credentials.";
            header('location:/admin/auth/login.php');
            exit;
        }
    }
    if (isset($_SESSION['login'])) {
        header('location:/admin/dashboard.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Retail Shop(Admin)</title>
        <link rel="stylesheet" href="/admin/css/vertical-layout-light/style.css">
        <link rel="shortcut icon" href="/admin/image/retail-store-icon-18.png" />
    </head>
    <body>
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-center auth px-0">
                    <div class="row w-100 mx-0">
                        <div class="col-lg-4 mx-auto">
                            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                                <div class="brand-logo">
                                    <a href="#">
                                        <span style="font-size:large; color:black;">
                                            Retail
                                            <b style="color:blue">
                                                Store
                                            </b>
                                        </span>
                                    </a>
                                </div>
                                <h4>Hello! let's get started</h4>
                                <h6 class="fw-light">Sign in to continue.</h6>
                                <form class="pt-3" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg" placeholder="Username" name="email" required
                                        <?php
                                            if (isset($_SESSION["name"])) {
                                                echo "value=\"".$_SESSION["name"]."\"";
                                                unset($_SESSION["name"]);
                                            }
                                        ?>
                                        >
                                        <label class="text-danger">
                                            <?php
                                                if (isset($_SESSION["name_alert"])) {
                                                    echo $_SESSION["name_alert"];
                                                    unset($_SESSION["name_alert"]);
                                                }
                                            ?>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-lg" placeholder="Password" name="password" required>
                                        <label class="text-danger">
                                            <?php
                                                if (isset($_SESSION["password_alert"])) {
                                                    echo $_SESSION["password_alert"];
                                                    unset($_SESSION["password_alert"]);
                                                }
                                            ?>
                                        </label>
                                    </div>
                                    <div class="mt-3">
                                        <input type="submit" name="submit" value="SIGN IN" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                        <br>
                                        <br>
                                        <label class="text-danger">
                                            <?php
                                                if (isset($_SESSION["alert"])) {
                                                    echo $_SESSION["alert"] ;
                                                    unset($_SESSION["alert"]);
                                                }
                                            ?>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>