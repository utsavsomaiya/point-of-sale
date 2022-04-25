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

const minimumSpendContainer = [0];

function renderMinimumSpendTemplate() {
    document.getElementsByClassName('minimum-spend-row-container')[0].innerHTML = '';
    for (const key in minimumSpendContainer) {
        minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container')[0];
        minimumSpendRowTemplate = document.getElementById('minimum-spend-template').innerHTML;
        minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
        if (key === "0") {
            document.querySelector('.remove-minimum-spend').setAttribute('class', 'd-none');
        }
    }
    removeObject = document.getElementsByClassName('remove-minimum-spend');
    [...removeObject].forEach((remove, i) => {
        const deleteIcon = document.createElement('i');
        deleteIcon.setAttribute('class', 'fa fa-trash-o');
        deleteIcon.setAttribute('onclick', `removeMinimumSpendRow(${i},${null})`);
        remove.appendChild(deleteIcon);
    });
}

function addMinimumSpendRow() {
    minimumSpendContainer.push(minimumSpendContainer[minimumSpendContainer.length-1]+1);
    renderMinimumSpendTemplate();
}

function removeMinimumSpendRow(index,flag) {
    if ((index+1) > -1) {
        minimumSpendContainer.splice((index+1), 1);
        if (flag == "error")
            sessionRenderMinimumSpendTemplate();
        else
            renderMinimumSpendTemplate();
    }
}

var discountDigit = document.getElementsByClassName('digit');
var minimumSpendAmount = document.getElementsByClassName('minimum-spend-amount');

function sessionRenderMinimumSpendTemplate() {
    document.getElementsByClassName('minimum-spend-row-container')[0].innerHTML = '';
    for (const key in minimumSpendContainer) {
        minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container')[0];
        minimumSpendRowTemplate = document.getElementById('minimum-spend-template').innerHTML;
        minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
        if (key === "0") {
            document.querySelector('.remove-minimum-spend').setAttribute('class', 'd-none');
        }
    }
    removeObject = document.getElementsByClassName('remove-minimum-spend');
    [...removeObject].forEach((remove, i) => {
        const deleteIcon = document.createElement('i');
        deleteIcon.setAttribute('class', 'fa fa-trash-o');
        deleteIcon.setAttribute('onclick', `removeMinimumSpendRow(${i},"error")`);
        remove.appendChild(deleteIcon);
    });
    for (i = 0; i < discountDigit.length; i++) {
        if (errorDiscountDigit.length > 0) {
            discountDigit[i].value = errorDiscountDigit[i];
        }
        if (errorMinimumSpendAmount.length > 0) {
            minimumSpendAmount[i].value = errorMinimumSpendAmount[i];
        }
        if (minimumSpendAlert[i] != undefined) {
            document.getElementsByClassName('minimum-spend-amount-error')[i].innerHTML = minimumSpendAlert[i];
        }
        if (digitAlert[i] != undefined  && digitAlert[i] != '[object Object]') {
            document.getElementsByClassName('digit-error')[i].innerHTML = digitAlert[i];
        }
    }
}

function sessionAddMinimumSpendRow() {
    minimumSpendContainer.push(minimumSpendContainer[minimumSpendContainer.length-1]+1);
    sessionRenderMinimumSpendTemplate();
}

const editMiniumSpendContainer = [];

function availableEditPageOption() {
    if (errorDiscountDigit.length > 0) {
        for (i = 0; i < (errorDiscountDigit.length); i++) {
            editMiniumSpendContainer.push(i);
        }
    }
    else {
        for (i = 0; i < (totalDiscounts); i++) {
            editMiniumSpendContainer.push(i);
        }
    }
    editPageFetchTemplate();
}

function editPageFetchTemplate() {
    document.getElementsByClassName('minimum-spend-row-container')[0].innerHTML = '';
    removeObject = document.getElementsByClassName('remove-minimum-spend');
    for (const key in editMiniumSpendContainer) {
        minimumSpendRowContainer = document.getElementsByClassName('minimum-spend-row-container')[0];
        minimumSpendRowTemplate = document.getElementById('minimum-spend-template').innerHTML;
        minimumSpendRowContainer.innerHTML += minimumSpendRowTemplate;
        if (key === "0") {
            document.querySelector('.remove-minimum-spend').setAttribute('class', 'd-none');
        }
    }
    [...removeObject].forEach((remove, i) => {
        const deleteIcon = document.createElement('i');
        deleteIcon.setAttribute('class', 'fa fa-trash-o');
        deleteIcon.setAttribute('onclick', `editRemoveMinimumSpendRow(${i})`);
        remove.appendChild(deleteIcon);
    });
    for (i = 0; i < editMiniumSpendContainer.length; i++){
        discountDigit[i].value = discountDigits[i];
        minimumSpendAmount[i].value = minimumSpendAmounts[i];
    }
    if (errorDiscountDigit.length > 0)
    {
        for (i = 0; i < discountDigit.length; i++) {
            if (errorDiscountDigit.length > 0) {
                discountDigit[i].value = errorDiscountDigit[i];
            }
            if (errorMinimumSpendAmount.length > 0) {
                minimumSpendAmount[i].value = errorMinimumSpendAmount[i];
            }
            if (minimumSpendAlert[i] != undefined) {
                document.getElementsByClassName('minimum-spend-amount-error')[i].innerHTML = minimumSpendAlert[i];
            }
            if (digitAlert[i] != undefined  && digitAlert[i] != '[object Object]') {
                document.getElementsByClassName('digit-error')[i].innerHTML = digitAlert[i];
            }
        }
    }
}

function editRemoveMinimumSpendRow(index) {
    if (index + 1 > -1) {
        discountDigits.splice(index + 1, 1);
        minimumSpendAmounts.splice(index + 1, 1);
        editMiniumSpendContainer.splice(index + 1, 1);
        editPageFetchTemplate();
    }
}

function editPageAddMinimumRow() {
    editMiniumSpendContainer.push(editMiniumSpendContainer[editMiniumSpendContainer.length-1]+1);
    editPageFetchTemplate();
}