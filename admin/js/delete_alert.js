function deleteProduct(productId) {
    if (confirm('Are you sure?')) {
        window.location.href = '/admin/product/delete_product.php?id=' + productId;
    }
}

function toast() {
    var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}

function deleteCategory(categoryId) {
    if (confirm('Are you sure?')) {
        window.location.href = '../category/delete_category.php?id=' + categoryId;
    }
}

function deleteDiscount(discountId) {
    if (confirm('Are you sure?')) {
        window.location.href = '../discount/delete_discount.php?id=' + discountId;
    }
}