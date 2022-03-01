</div>
</div>
</div>
<script src="/admin/vendors/js/vendor.bundle.base.js"></script>
<script src="/admin/js/delete_alert.js"></script>
<?php
if (isset($_SESSION['msg'])) {
    ?>
<div id="snackbar"> <?php echo $_SESSION['msg']; ?> </div>
<?php
}
?>
<script>
    <?php
    if (isset($_SESSION['msg'])) {
        echo "toast()";
    }
    ?>
</script>
</body>
</html>
