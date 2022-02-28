function stateChanged($id, $state) {
    if ($state == 1) {
        $state = $state + 1;
    } else {
        $state = $state - 1;
    }
  $.ajax({
            url: "update.php",
            data: {id : $id, state: $state},
            type: "POST"
        });
}
