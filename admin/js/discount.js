function discountStatusChanged(id, status) {
    if (status == 1) {
        status = status + 1;
    } else {
        status = status - 1;
    }
  $.ajax({
            url: "edit.php",
            data: {id : id, status: status},
            type: "POST"
        });
}
