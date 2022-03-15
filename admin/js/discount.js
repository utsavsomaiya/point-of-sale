function discountStatusChanged(id, status) {
    if (status == 1) {
        status = status + 1;
    } else {
        status = status - 1;
    }
    $.acountax({
        url: "edit.php",
        data: {
            id: id,
            status: status
        },
        type: "POST"
    });
}

var count = 1;

function addElementFromInsertPage() {
    var discountDiv = document.getElementById('discount-tiers-container');
    var discountDivClone = discountDiv.cloneNode(true);
    discountDivClone.children[0].children[1].value = "";
    discountDivClone.children[1].children[1].value = "";
    discountDivClone.children[2].style.display = "block";
    discountDivClone.children[2].id = "remove-div-" + count;
    discountDivClone.setAttribute('id', 'discount-tiers-container-' + count);
    document.getElementById('container').appendChild(discountDivClone);

    var remove = document.createElement('i');
    remove.setAttribute('class', 'fa fa-trash-o');
    remove.setAttribute('onclick', 'remove('+count+')');
    document.getElementById('remove-div-' + count).appendChild(remove);

    count++;
}

function addElementFromEditPage() {
    var discountDiv = document.getElementById('discount-tiers-container');
    var discountDivClone = discountDiv.cloneNode(true);
    discountDivClone.children[0].children[1].value = "";
    discountDivClone.children[1].children[1].value = "";
    discountDivClone.children[2].style.display = "block";
    discountDivClone.children[2].id = "remove-div-" + totalDiscounts;
    discountDivClone.setAttribute('id', 'discount-tiers-container-' + totalDiscounts);
    document.getElementById('container').appendChild(discountDivClone);

    var remove = document.createElement('i');
    remove.setAttribute('class', 'fa fa-trash-o');
    remove.setAttribute('onclick', 'remove('+totalDiscounts+')');
    document.getElementById('remove-div-' + totalDiscounts).appendChild(remove);

    totalDiscounts++;
}

function remove(index) {
    var select = document.getElementById('discount-tiers-container-'+index);
    select.parentNode.removeChild(select);
    count--;
}