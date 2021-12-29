<?php
    session_start();
    include 'header.php';
    //session_start();
    ?>
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <?php  echo 'Hello, How are you '.$_SESSION['name'].'?';?>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php
    include 'footer.php';
