</div>
</div>
<script src="/vendors/js/vendor.bundle.base.js"></script>
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
            window.location.href='../product/delete_category.php?id='+id1;
        }
    }
</script>
</body>
</html>
