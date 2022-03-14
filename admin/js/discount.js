function discountStatusChanged(id, status) {
    if (status == 1) {
        status = status + 1;
    } else {
        status = status - 1;
    }
    $.ajax({
        url: "edit.php",
        data: {
            id: id,
            status: status
        },
        type: "POST"
    });
}

var j = 1;

function addInsert() {
    var discountDiv = document.getElementById('discount-tiers-container');
    var discountDivClone = discountDiv.cloneNode(true);
    discountDivClone.children[0].children[1].value = "";
    discountDivClone.children[1].children[1].value = "";
    discountDivClone.children[2].style.display = "block";
    discountDivClone.children[2].id = "remove-div-" + j;
    discountDivClone.setAttribute('id', 'discount-tiers-container-' + j);
    document.getElementById('container').appendChild(discountDivClone);

    var remove = document.createElement('i');
    remove.setAttribute('class', 'fa fa-trash-o');
    remove.setAttribute('onclick', 'remove('+j+')');
    document.getElementById('remove-div-' + j).appendChild(remove);

    j++;
}

function add() {
    var discountDiv = document.getElementById('discount-tiers-container');
    var discountDivClone = discountDiv.cloneNode(true);
    discountDivClone.children[0].children[1].value = "";
    discountDivClone.children[1].children[1].value = "";
    discountDivClone.children[2].style.display = "block";
    discountDivClone.children[2].id = "remove-div-" + index;
    discountDivClone.setAttribute('id', 'discount-tiers-container-' + index);
    document.getElementById('container').appendChild(discountDivClone);

    var remove = document.createElement('i');
    remove.setAttribute('class', 'fa fa-trash-o');
    remove.setAttribute('onclick', 'remove('+index+')');
    document.getElementById('remove-div-' + index).appendChild(remove);

    index++;
}



function remove(index) {
    var select = document.getElementById('discount-tiers-container-'+index);
    select.parentNode.removeChild(select);
    j--;
}