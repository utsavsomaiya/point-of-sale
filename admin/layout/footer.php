</div>
</div>
<script src="/admin/vendors/js/vendor.bundle.base.js"></script>
<script>
    <?php
    if (isset($_SESSION['msg'])) {
        echo "myFunction()";
    }
    ?>
</script>
<script>
    function alert_c(id1) {
        if (confirm('Are you sure?')) {
            window.location.href='../category/delete_category.php?id='+id1;
        }
    }
</script>
</body>
</html>
