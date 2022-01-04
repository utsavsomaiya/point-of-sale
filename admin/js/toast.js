function alert(id) {
    if (confirm('Are you sure?')) {
        window.location.href = '/admin/product/delete_product.php?id=' + id;
    }
}

function myFunction() {
    var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}

function alert_c(id1) {
    if (confirm('Are you sure?')) {
        window.location.href = '../category/delete_category.php?id=' + id1;
    }
}