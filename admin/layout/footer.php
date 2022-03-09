</div>
</div>
<script src="/admin/vendors/js/vendor.bundle.base.js"></script>
<script src="/admin/js/delete_alert.js"></script>
<?php if (isset($_SESSION['message'])) { ?>
    <div id="snackbar">
        <?php echo $_SESSION['message']; ?>
    </div>
<?php } ?>
<script>
    <?php
        if (isset($_SESSION['message'])) {
            echo "toast()";
        }
    ?>
</script>
<?php unset($_SESSION['message']); ?>
</body>
</html>
