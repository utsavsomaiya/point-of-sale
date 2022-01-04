<?php
    session_start();
    if (isset($_POST['s1'])) {
        require '../layout/db_connect.php';
        $name = $_POST['email'];
        $password = $_POST['password'];
        $abc = $pdo->prepare("select id from admin where name='$name' and password='$password' and 1=1");
        $abc->execute();

        $result = $abc->fetchAll();
        if (!$result) {
            $_SESSION['alert'] = 'User Id and Password are Wrong';
        } else {
            $_SESSION['name'] = $_POST['email'];
            $_SESSION["login"] = "OK";
            header('location:../dashboard.php');
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
    <title>Star Admin2 </title>
    <link rel="stylesheet" href="/admin/css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="/admin/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="/admin/images/logo.svg" alt="logo">
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="fw-light">Sign in to continue.</h6>
                            <form class="pt-3" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" id="exampleInputEmail1"
                                        placeholder="Username" name="email"
                                        value="<?php if (isset($_POST["email"])) {
    echo $_POST["email"];
} ?>"
                                        >
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg"
                                        id="exampleInputPassword1" placeholder="Password" name="password">
                                </div>
                                <div class="mt-3">
                                    <input type="submit" name="s1" value="SIGN IN"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                        <br><br><label style="color:red;"><?php if (isset($_SESSION["alert"])) {
    echo $_SESSION["alert"] ;
} ?></label>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <a href="#" class="auth-link text-black">Forgot password?</a>
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
