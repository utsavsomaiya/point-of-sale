<?php
    session_start();
    include '../layout/header.php';
    try {
        $pdo =  new PDO('mysql:host=127.0.0.1;dbname=abc', 'root', '1234');
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $fetch = $pdo->prepare("select * from category where id='$id'");
        $fetch->execute();
        $result = $fetch->fetchAll();
        foreach ($result as $r) {
            $name = $r['name'];
        }
    }
    if (isset($_POST['submit'])) {
        if (!empty($_POST['pname'])) {
            $pname = $_POST['pname'];
            $fetch = $pdo->prepare("update category set name='$pname' where id='$id'");
            $result = $fetch->execute();
            if (isset($result)) {
                $_SESSION['msg'] = "Update Successfully";
                header('location:../product/show_category.php');
            } else {
                echo 'No';
            }
        } else {
            $alert = "Please enter the data..";
        }
    }
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit category</h4>
                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?= $id; ?>">
                                <label for="exampleInputUsername1">Category Name</label>
                                <input type="text" class="form-control" id="exampleInputUsername1"
                                    placeholder="Category Name" name="pname" value="<?= $name; ?>">
                                    <label style="color:red;">
                                    <?php
                                    if (isset($alert)) {
                                        echo $alert;
                                    }
                                    ?>
                                    </label>
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="submit" onclick="myFunction()">Submit</button>
                            <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layout/footer.php'; ?>